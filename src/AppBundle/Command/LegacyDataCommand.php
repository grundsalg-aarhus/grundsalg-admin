<?php

namespace AppBundle\Command;

use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Legacy data command.
 */
class LegacyDataCommand extends ContainerAwareCommand {

  /**
   * {@inheritdoc}
   */
  protected function configure() {
    $this
      ->setName('app:import-legacy-data')
      ->setDescription('Imports legacy data.')
      ->addArgument('file', InputArgument::REQUIRED, 'The path to the json data dump.');
  }

  private $output;

  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    $this->output = $output;
    $filename = $input->getArgument('file');
    $data = $this->getData($filename);

    if ($data) {
      $connection = $this->getContainer()->get('doctrine.dbal.default_connection');
      foreach ($data as $table => $rows) {
        $output->writeln(sprintf('%s (#rows: %d)', $table, count($rows)));

        foreach ($rows as $row) {
          $columns = array_keys($row);
          $sql = 'INSERT INTO ' . $table . ' (' . implode(', ', $columns);
          $sql .= ') VALUES (' . implode(', ', array_map(function ($column) {
            return ':' . $column;
          }, $columns));
          $sql .= ');';
          try {
            $stmt = $connection->prepare($sql);
            $stmt->execute($row);
          }
          catch (ForeignKeyConstraintViolationException $e) {
            var_export($row);
            throw $e;
          }
        }
      }
    }
  }

  /**
   * Get data to import.
   *
   * @param string $filename
   *   The filename to read from. Use '-' to read from stdin.
   *
   * @return array
   *   The data to import.
   */
  private function getData($filename) {
    $content = '';

    if ($filename === '-') {
      $content = file_get_contents("php://stdin");
    }
    elseif (file_exists($filename)) {
      $content = file_get_contents($filename);
    }
    else {
      throw new \Exception('File ' . $filename . ' does not exist');
    }

    $data = @json_decode($content, TRUE);

    if (!$data) {
      throw new \Exception('No data read');
    }

    // Sort tables by dependencies.
    // Order in which table data must be imported.
    $tables = [
      'PostBy',
      'Lokalsamfund',
      'Lokalplan',
      'Delomraade',
      'Landinspektoer',
      'Salgsomraade',
      'Grund',
      'Salgshistorik',
      'Opkoeb',
      'Interessent',
      'InteressentGrundMapping',
      'Keyword',
      'KeywordValue',
      'Users',
    ];

    uksort($data, function ($a, $b) use ($tables) {
      return array_search($a, $tables) <=> array_search($b, $tables);
    });

    // Clean up data.
    foreach ($data as $table => &$rows) {
      foreach ($rows as &$row) {
        if ($table == 'Delomraade') {
          if (in_array($row['lokalplanId'], ['LP. 763', 'Areal ved Skovvejen, Aarhus N'], TRUE)) {
            $this->setValue($table, $row, 'lokalplanId', NULL);
          }
        }

        if ($table == 'Salgsomraade') {
          if (in_array($row['delomraadeId'], ['', '0', '1020-TEST'], TRUE)) {
            $this->setValue($table, $row, 'delomraadeId', NULL);
          }
          if (in_array($row['landinspektorId'], ['', '0'], TRUE)) {
            $this->setValue($table, $row, 'landinspektorId', NULL);
          }
          if (in_array($row['lokalPlanId'], ['0'], TRUE)) {
            $this->setValue($table, $row, 'lokalPlanId', NULL);
          }
          if (in_array($row['postById'], ['0'], TRUE)) {
            $this->setValue($table, $row, 'postById', NULL);
          }
        }

        if ($table == 'Grund') {
          if (in_array($row['koeberPostById'], ['', '0', '239'], TRUE)) {
            $this->setValue($table, $row, 'koeberPostById', NULL);
          }
          if (in_array($row['medKoeberPostById'], ['', '0', '239'], TRUE)) {
            $this->setValue($table, $row, 'medKoeberPostById', NULL);
          }
          if (in_array($row['salgsomraadeId'], ['0', '15', '268', '271'])) {
            $this->setValue($table, $row, 'salgsomraadeId', NULL);
          }
        }

        if ($table == 'Salgshistorik') {
          // @codingStandardsIgnoreLine
          if (in_array($row['grundId'], ['0', '96', '115', '1299', '1300', '1745', '1746', '1753', '1757', '1769', '1770', '1742', '1771', '1748', '1666', '1654'], TRUE)) {
            $this->setValue($table, $row, 'grundId', NULL);
          }
          if (in_array($row['koeberPostById'], ['', '0', '239'], TRUE)) {
            $this->setValue($table, $row, 'koeberPostById', NULL);
          }
          if (in_array($row['medKoeberPostById'], ['', '0'], TRUE)) {
            $this->setValue($table, $row, 'medKoeberPostById', NULL);
          }
        }

        if ($table == 'Opkoeb') {
          if (in_array($row['lpId'], ['0'], TRUE)) {
            $this->setValue($table, $row, 'lpId', NULL);
          }
        }

        if ($table == 'Interessent') {
          if (in_array($row['koeberPostById'], ['', '0'], TRUE)) {
            $this->setValue($table, $row, 'koeberPostById', NULL);
          }
          if (in_array($row['medKoeberPostById'], ['', '0'], TRUE)) {
            $this->setValue($table, $row, 'medKoeberPostById', NULL);
          }
        }

        if ($table == 'InteressentGrundMapping') {
          // @codingStandardsIgnoreLine
          if (in_array($row['grundId'], ['0', '115', '1746', '1747', '1748', '1749', '1753', '1757', '1758', '1759', '1760', '1770', '1772', '1773', '2427', '2427'], TRUE)) {
            $this->setValue($table, $row, 'grundId', NULL);
          }
        }
      }
    }

    return $data;
  }

  /**
   * Set value in import row.
   *
   * @param string $table
   *   The table name.
   * @param array $row
   *   The row.
   * @param string $column
   *   The column name.
   * @param mixed $value
   *   The value.
   */
  private function setValue(string $table, array &$row, string $column, $value) {
    $output = $this->output instanceof ConsoleOutputInterface ? $this->output->getErrorOutput() : $this->output;

    $output->writeln(sprintf('<comment>Warning: %s#%d.%s: %s -> %s</comment>', $table, $row['id'], $column, var_export($row[$column], TRUE), var_export($value, TRUE)));
    $row[$column] = $value;
  }

}
