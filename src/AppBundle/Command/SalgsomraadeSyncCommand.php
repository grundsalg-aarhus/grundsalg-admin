<?php

namespace AppBundle\Command;

use AppBundle\Entity\Lokalplan;
use AppBundle\Entity\Salgsomraade;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
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
      ->setDescription('Sync alle salgsomraader til Drupal')
      ->addOption('id', null, InputOption::VALUE_OPTIONAL, 'Limit sync to "salgsområde" with id');
  }

  /**
   * {@inheritdoc}
   *
   * Finds all "Salgsområder" and send update request to the front-end web-site.
   */
  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $this->output = $output;
    $id = $input->getOption('id');

    $communicationService = $this->getContainer()->get('app.website_communication_service');

    $em = $this->getContainer()->get('doctrine')->getManager();

    if ($id) {
      $salgsomraader = [];
      $salgsomraader[] = $em->getRepository('AppBundle:Salgsomraade')->find($id);
    } else {
      $salgsomraader = $em->getRepository('AppBundle:Salgsomraade')->findAll();
    }

    $count = [
      'created' => 0,
      'updated' => 0,
      'error' => 0,
    ];
    $total = count($salgsomraader);
    $pointer = 1;

    /** @var Salgsomraade $area */
    foreach ($salgsomraader as $area) {
      $content = $communicationService->syncDataToWebsite($area);
      $message = $content['message'] ?? '';

      if (isset($content['error']) && true === $content['error'] ) {
        $count['error']++;
        $actionLabel = 'Error';
      } else if ($message == 'updated') {
        $count['updated']++;
        $actionLabel = 'Updated';
      }
      else {
        $count['created']++;
        $actionLabel = 'Created';
      }

      $output->writeln('('.$pointer.'/'.$total.') '.$actionLabel.' id: ' . $area->getId() . ' Title: "' . $area->getTitel() . '" Message: "' . $message . '"');

      $pointer++;
    }

    $output->writeln(str_repeat("=", 20) . ' Synced completed ' . str_repeat("=", 20));
    $output->writeln('Created: ' . $count['created']);
    $output->writeln('Updated: ' . $count['updated']);
    $output->writeln('Error: ' . $count['error']);
  }
}
