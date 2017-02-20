<?php

namespace AppBundle\Command;

use AppBundle\Entity\Lokalplan;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Sync salgsomraader command
 */
class SalgsomraadeSyncCommand extends ContainerAwareCommand
{

  /**
   * {@inheritdoc}
   */
  protected function configure()
  {
    $this
      ->setName('app:salgsomraader:sync')
      ->setDescription('Sync alle salgsomraader til Drupal');
  }

  /**
   * {@inheritdoc}
   *
   * Finds all "Salgsområder" and send update request to the front-end web-site.
   */
  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $this->output = $output;

    $comService = $this->getContainer()->get('grundsalg.communication');

    $em = $this->getContainer()->get('doctrine')->getManager();
    $salgsomraader = $em->getRepository('AppBundle:Salgsomraade')->findAll();
    $count = 0;

    foreach ($salgsomraader as $salgsomraade) {
      $comService->saveSalgsomraade($salgsomraade);
      $output->writeln('Sync ' . $salgsomraade->getId() . ' ' . $salgsomraade->getTitel());
      $count++;
    }

    $output->writeln('Synced ' . $count . ' salgsomraader');
  }
}
