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
class LegacyDataCommand extends ContainerAwareCommand
{

  /**
   * {@inheritdoc}
   */
  protected function configure()
  {
    $this
      ->setName('app:import-legacy-data')
      ->setDescription('Imports legacy data.')
      ->addArgument('file', InputArgument::REQUIRED, 'The path to the json data dump.');
  }

  private $output;

  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output)
  {
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
          } catch (ForeignKeyConstraintViolationException $e) {
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
  private function getData($filename)
  {

    if ($filename === '-') {
      $content = file_get_contents("php://stdin");
    } elseif (file_exists($filename)) {
      $content = file_get_contents($filename);
    } else {
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
          if (!$this->validateIdExists($data['Lokalplan'], $row['lokalplanId'])) {
            $this->setValue($table, $row, 'lokalplanId', NULL);
          }
        }

        if ($table == 'Salgsomraade') {
          if (!$this->validateIdExists($data['Delomraade'], $row['delomraadeId'])) {
            $this->setValue($table, $row, 'delomraadeId', NULL);
          }
          if (!$this->validateIdExists($data['Landinspektoer'], $row['landinspektorId'])) {
            $this->setValue($table, $row, 'landinspektorId', NULL);
          }
          if (!$this->validateIdExists($data['Lokalplan'], $row['lokalPlanId'])) {
            $this->setValue($table, $row, 'lokalPlanId', NULL);
          }
          if (!$this->validateIdExists($data['PostBy'], $row['postById'])) {
            $this->setValue($table, $row, 'postById', NULL);
          }
        }

        if ($table == 'Grund') {
          if (!$this->validateIdExists($data['PostBy'], $row['koeberPostById'])) {
            $this->setValue($table, $row, 'koeberPostById', NULL);
          }
          if (!$this->validateIdExists($data['PostBy'], $row['medKoeberPostById'])) {
            $this->setValue($table, $row, 'medKoeberPostById', NULL);
          }
          if (!$this->validateIdExists($data['PostBy'], $row['postbyId'])) {
            $this->setValue($table, $row, 'postbyId', NULL);
          }
          if (!$this->validateIdExists($data['Salgsomraade'], $row['salgsomraadeId'])) {
            $this->setValue($table, $row, 'salgsomraadeId', NULL);
          }
          if (!$this->validateIdExists($data['Landinspektoer'], $row['landInspektoerId'])) {
            $this->setValue($table, $row, 'landInspektoerId', NULL);
          }

          // Ensure that "husNummer" is either null or numeric for safe column type conversion (longtext -> INT)
          if (!is_numeric($row['husNummer'])) {
            if (is_numeric(trim($row['husNummer']))) {
              $this->setValue($table, $row, 'husNummer', trim($row['husNummer']));
            } else if (empty($row['husNummer'])) {
              $this->setValue($table, $row, 'husNummer', NULL);
            } else {
              $this->throwException($table, $row, 'husNummer', 'Cannot be safely converted to nummeric value');
            }
          }

          // Ensure that "annonceresEj" is either null or 0/1 for safe column type conversion (varchar(50) -> BOOL)
          if ($row['annonceresEj'] === 'X') {
            $this->setValue($table, $row, 'annonceresEj', 1);
          } else if (empty($row['annonceresEj'])) {
            $this->setValue($table, $row, 'annonceresEj', NULL);
          } else {
            $this->throwException($table, $row, 'annonceresEj', 'Cannot be safely converted to bool value');
          }
        }

        if ($table == 'Salgshistorik') {
          if (!$this->validateIdExists($data['Grund'], $row['grundId'])) {
            $this->setValue($table, $row, 'grundId', NULL);
          }
          if (!$this->validateIdExists($data['PostBy'], $row['koeberPostById'])) {
            $this->setValue($table, $row, 'koeberPostById', NULL);
          }
          if (!$this->validateIdExists($data['PostBy'], $row['medKoeberPostById'])) {
            $this->setValue($table, $row, 'medKoeberPostById', NULL);
          }
        }

        if ($table == 'Opkoeb') {
          if (!$this->validateIdExists($data['Lokalplan'], $row['lpId'])) {
            $this->setValue($table, $row, 'lpId', NULL);
          }
        }

        if ($table == 'Interessent') {
          if (!$this->validateIdExists($data['PostBy'], $row['koeberPostById'])) {
            $this->setValue($table, $row, 'koeberPostById', NULL);
          }
          if (!$this->validateIdExists($data['PostBy'], $row['medKoeberPostById'])) {
            $this->setValue($table, $row, 'medKoeberPostById', NULL);
          }
        }

        if ($table == 'InteressentGrundMapping') {
          if (!$this->validateIdExists($data['Grund'], $row['grundId'])) {
            $this->setValue($table, $row, 'grundId', NULL);
          }
        }

        if ($table == 'Landinspektoer') {
          // Ensure that "active" is either null or 0/1 for safe column type conversion (int(11) -> BOOL)
          if ($row['active'] != 1 && $row['active'] != 0) {
            $this->throwException($table, $row, 'active', 'Cannot be safely converted to bool value');
          }
        }

        if ($table == 'Lokalsamfund') {
          // Ensure that "active" is either null or 0/1 for safe column type conversion (int(11) -> BOOL)
          if ($row['active'] !== 1 && $row['active'] !== 0) {
            $this->throwException($table, $row, 'active', 'Cannot be safely converted to bool value');
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
  private function setValue(string $table, array &$row, string $column, $value)
  {
    $output = $this->output instanceof ConsoleOutputInterface ? $this->output->getErrorOutput() : $this->output;

    $output->writeln(sprintf('<comment>Warning: %s#%d.%s: %s -> %s</comment>', $table, $row['id'], $column, var_export($row[$column], TRUE), var_export($value, TRUE)));
    $row[$column] = $value;
  }

  /**
   * Throw exception if for given table, row, column with error message
   *
   * @param string $table
   * @param array $row
   * @param string $column
   * @param string $error_message
   * @throws \Exception
   */
  private function throwException(string $table, array &$row, string $column, string $error_message)
  {
    $message = sprintf('Conversion Error: %s#%d.%s: %s -> %s', $table, $row['id'], $column, var_export($row[$column], TRUE), $error_message);

    throw new \Exception($message);
  }

  /**
   * Validate that given id exists in referenced table
   *
   * @param array $table
   * @param $id
   * @return bool
   */
  private function validateIdExists(array &$table, $id) {
    foreach ($table as $row) {
      if($row['id'] === $id) {
        return true;
      }
    }

    return false;
  }

}
