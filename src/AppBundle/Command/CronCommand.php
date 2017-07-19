<?php

namespace AppBundle\Command;

use AppBundle\DBAL\Types\GrundStatus;
use AppBundle\Entity\Grund;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command to run cron jobs.
 */
class CronCommand extends ContainerAwareCommand {
  /**
   * A  console output stream.
   *
   * @var \Symfony\Component\Console\Output\OutputInterface
   */
  protected $output;

  /**
   * An entity manager.
   *
   * @var \Doctrine\ORM\EntityManagerInterface
   */
  protected $manager;

  /**
   * {@inheritdoc}
   */
  protected function configure() {
    $this->setName('app:cron')
      ->setDescription('Run cron jobs');
  }

  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    $this->output = $output;
    $this->manager = $this->getContainer()->get('doctrine.orm.entity_manager');

    $this->endAuction();
    $this->setAnnounced();
  }

  /**
   * Mark auctions on select Grunds as ended.
   */
  private function endAuction()
  {
      $queryBuilder = $this->manager->getRepository(Grund::class)->createQueryBuilder('g');
    $entities = $queryBuilder
      ->andWhere('g.status in (:statuses)')
      ->setParameter('statuses', [GrundStatus::FREMTIDIG, GrundStatus::ANNONCERET])
      ->andWhere('g.auktionslutdato IS NOT NULL')
      ->andWhere('g.auktionslutdato < :now')
      ->setParameter('now', new \DateTime(null, new \DateTimeZone('UTC')))
      ->getQuery()->getResult();

    $this->write(sprintf('End auction; #entities: %d', count($entities)));
    foreach ($entities as $entity) {
      $this->write(
        sprintf(
          "% 8d: %s\t%s",
          $entity->getId(),
          (string)$entity,
          $entity->getAuktionslutdato()->format(\DateTime::ISO8601)
        )
      );
      $entity->setStatus(GrundStatus::AUKTION_SLUT);
      $this->manager->persist($entity);
    }
    $this->manager->flush();
  }

  /**
   * Mark select Grunds as announced.
   */
  private function setAnnounced() {
    $queryBuilder = $this->manager->getRepository(Grund::class)->createQueryBuilder('g');
    $entities = $queryBuilder
      ->andWhere('g.status IN (:statuses)')
      ->setParameter('statuses', [GrundStatus::FREMTIDIG])
      ->andWhere('g.datoannonce <= :now')
      ->setParameter('now', new \DateTime(null, new \DateTimeZone('UTC')))
      ->getQuery()->getResult();

    $this->write(sprintf('Set announced; #entities: %d', count($entities)));
    foreach ($entities as $entity) {
      $this->write(
        sprintf(
          "% 8d: %s\t%s",
          $entity->getId(),
          (string)$entity,
          $entity->getDatoAnnonce()->format(\DateTime::ISO8601)
        )
      );
      $entity->setStatus(GrundStatus::ANNONCERET);
      $this->manager->persist($entity);
    }
    $this->manager->flush();
  }

  /**
   * Write message(s) to console.
   */
  private function write($messages) {
    if ($this->output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
      $this->output->writeln($messages);
    }
  }

}
