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

    $communicationService = $this->getContainer()->get('app.website_communication_service');

    $em = $this->getContainer()->get('doctrine')->getManager();
    $salgsomraader = $em->getRepository('AppBundle:Salgsomraade')->findAll();
    $count = [
      'created' => 0,
      'updated' => 0,
      'error' => 0,
    ];
    foreach ($salgsomraader as $area) {
      $content = $communicationService->syncDataToWebsite($area);
      $message = $content['message'] ?? '';
      $output->writeln('Synced id: ' . $area->getId() . ' Title: "' . $area->getTitel() . '" Message: "' . $message . '"');

      if (isset($content['error']) && $content['error']) {
        $count['error']++;
      }
      else if ($message == 'updated') {
        $count['updated']++;
      }
      else {
        $count['created']++;
      }
    }

    $output->writeln(str_repeat("=", 20) . ' Synced completed ' . str_repeat("=", 20));
    $output->writeln('Created: ' . $count['created']);
    $output->writeln('Updated: ' . $count['updated']);
    $output->writeln('Error: ' . $count['error']);
  }
}
