<?php

namespace AppBundle\Command;

use AppBundle\Entity\Lokalplan;
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
  private $changeCount;
  private $changeForeignKeyCount;

  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $this->output = $output;

    $output->writeln('------------------');
    $output->writeln('Starting fagsystem import');

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

    $output->writeln('Conversion summary');
    $this->printChangeLog($this->changeCount);

    $output->writeln('Foreign Key Nulled summary');
    $this->printChangeLog($this->changeForeignKeyCount);
  }

  /**
   * Get data to import.
   *
   * @param string $filename
   *   The filename to read from. Use '-' to read from stdin.
   *
   * @return array
   *   The data to import.
   *
   * @throws \Exception
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

        // PostBy
        if ($table == 'PostBy') {
          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(100))
          $this->validateLengthShorterThanOrEqual($table, $row, 'city', 100);
        }

        // Lokalsamfund

        if ($table == 'Lokalsamfund') {
          // Ensure that "active" is either null or 0/1 for safe column type conversion (int(11) -> BOOL)
          if ($row['active'] !== 1 && $row['active'] !== 0) {
            $this->throwException($table, $row, 'active', 'Cannot be safely converted to bool value');
          }

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(50))
          $this->validateLengthShorterThanOrEqual($table, $row, 'name', 50);
        }


        // Lokalplan

        if ($table == 'Lokalplan') {
          if (!$this->validateIdExists($data['Lokalsamfund'], $row['lsnr'])) {
            $this->setForeignKeyNull($table, $row, 'lsnr', NULL);
          }

          // Ensure that "samletAreal" is either null or numeric for safe column type conversion (LONGTEXT -> INT)
          $this->convertToNumeric($table, $row, 'samletAreal');

          // Ensure that "salgbartAreal" is either null or numeric for safe column type conversion (LONGTEXT -> INT)
          $this->convertToNumeric($table, $row, 'salgbartAreal');

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(255))
          $this->validateLengthShorterThanOrEqual($table, $row, 'titel', 255);

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(50))
          $this->validateLengthShorterThanOrEqual($table, $row, 'projektLeder', 50);

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(20))
          $this->validateLengthShorterThanOrEqual($table, $row, 'telefon', 20);

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(255))
          $this->validateLengthShorterThanOrEqual($table, $row, 'lokalPlanLink', 255);

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(50))
          $this->validateLengthShorterThanOrEqual($table, $row, 'nr', 50);
        }


        // Delomraade

        if ($table == 'Delomraade') {
          if (!$this->validateIdExists($data['Lokalplan'], $row['lokalplanId'])) {
            $this->setForeignKeyNull($table, $row, 'lokalplanId', NULL);
          }

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(50))
          $this->validateLengthShorterThanOrEqual($table, $row, 'anvendelse', 50);

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(50))
          $this->validateLengthShorterThanOrEqual($table, $row, 'mulighedFor', 50);
        }


        // Landinspektoer

        if ($table == 'Landinspektoer') {
          if (!$this->validateIdExists($data['PostBy'], $row['postnrId'])) {
            $this->setForeignKeyNull($table, $row, 'postnrId', NULL);
          }

          // Ensure that "active" is either null or 0/1 for safe column type conversion (int(11) -> BOOL)
          if ($row['active'] !== 1 && $row['active'] !== 0) {
            $this->throwException($table, $row, 'active', 'Cannot be safely converted to bool value');
          }

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(50))
          $this->validateLengthShorterThanOrEqual($table, $row, 'adresse', 50);

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(50))
          $this->validateLengthShorterThanOrEqual($table, $row, 'email', 50);

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(20))
          $this->validateLengthShorterThanOrEqual($table, $row, 'mobil', 20);

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(100))
          $this->validateLengthShorterThanOrEqual($table, $row, 'navn', 100);

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(20))
          $this->validateLengthShorterThanOrEqual($table, $row, 'telefon', 20);
        }


        // Salgsomraade

        if ($table == 'Salgsomraade') {
          if (!$this->validateIdExists($data['Delomraade'], $row['delomraadeId'])) {
            $this->setForeignKeyNull($table, $row, 'delomraadeId', NULL);
          }
          if (!$this->validateIdExists($data['Landinspektoer'], $row['landinspektorId'])) {
            $this->setForeignKeyNull($table, $row, 'landinspektorId', NULL);
          }
          if (!$this->validateIdExists($data['Lokalplan'], $row['lokalPlanId'])) {
            $this->setForeignKeyNull($table, $row, 'lokalPlanId', NULL);
          }
          if (!$this->validateIdExists($data['PostBy'], $row['postById'])) {
            $this->setForeignKeyNull($table, $row, 'postById', NULL);
          }

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(255))
          $this->validateLengthShorterThanOrEqual($table, $row, 'titel', 255);

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(30))
          $this->validateLengthShorterThanOrEqual($table, $row, 'type', 30);

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(20))
          $this->validateLengthShorterThanOrEqual($table, $row, 'matrikkel1', 20);

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(20))
          $this->validateLengthShorterThanOrEqual($table, $row, 'matrikkel2', 20);

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(60))
          $this->validateLengthShorterThanOrEqual($table, $row, 'ejerlav', 60);

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(60))
          $this->validateLengthShorterThanOrEqual($table, $row, 'vej', 60);

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(255))
          $this->validateLengthShorterThanOrEqual($table, $row, 'gisUrl', 255);

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(50))
          $this->validateLengthShorterThanOrEqual($table, $row, 'tilsluttet', 50);

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(50))
          $this->validateLengthShorterThanOrEqual($table, $row, 'sagsNr', 50);
        }


        // Grund

        if ($table == 'Grund') {
          if (!$this->validateIdExists($data['PostBy'], $row['koeberPostById'])) {
            $this->setForeignKeyNull($table, $row, 'koeberPostById', NULL);
          }
          if (!$this->validateIdExists($data['PostBy'], $row['medKoeberPostById'])) {
            $this->setForeignKeyNull($table, $row, 'medKoeberPostById', NULL);
          }
          if (!$this->validateIdExists($data['PostBy'], $row['postbyId'])) {
            $this->setForeignKeyNull($table, $row, 'postbyId', NULL);
          }
          if (!$this->validateIdExists($data['Salgsomraade'], $row['salgsomraadeId'])) {
            $this->setForeignKeyNull($table, $row, 'salgsomraadeId', NULL);
          }
          if (!$this->validateIdExists($data['Landinspektoer'], $row['landInspektoerId'])) {
            $this->setForeignKeyNull($table, $row, 'landInspektoerId', NULL);
          }

          // Ensure that "husNummer" is either null or numeric for safe column type conversion (longtext -> INT)
          $this->convertToNumeric($table, $row, 'husNummer');

          // Ensure that "bogstav" is either null or safe length (varchar 30)
          $this->validateLengthShorterThanOrEqual($table, $row, 'bogstav', 30);

          // Ensure that "annonceresEj" is either null or 0/1 for safe column type conversion (varchar(50) -> BOOL)
          $this->convertXToBoolean($table, $row, 'annonceresEj');

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(20))
          $this->validateLengthShorterThanOrEqual($table, $row, 'mnr', 20);

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(20))
          $this->validateLengthShorterThanOrEqual($table, $row, 'mnr2', 20);

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(60))
          $this->validateLengthShorterThanOrEqual($table, $row, 'delAreal', 60);

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(60))
          $this->validateLengthShorterThanOrEqual($table, $row, 'ejerlav', 60);

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(60))
          $this->validateLengthShorterThanOrEqual($table, $row, 'vej', 60);

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(255))
          $this->validateLengthShorterThanOrEqual($table, $row, 'urlGIS', 255);

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(50))
          $this->validateLengthShorterThanOrEqual($table, $row, 'tilsluttet', 50);

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(30))
          $this->validateLengthShorterThanOrEqual($table, $row, 'prisKorrektion1', 30);

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(30))
          $this->validateLengthShorterThanOrEqual($table, $row, 'prisKorrektion2', 30);

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(30))
          $this->validateLengthShorterThanOrEqual($table, $row, 'prisKorrektion3', 30);

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(30))
          $this->validateLengthShorterThanOrEqual($table, $row, 'salgsType', 30);

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(255))
          $this->validateLengthShorterThanOrEqual($table, $row, 'navn', 255);

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(100))
          $this->validateLengthShorterThanOrEqual($table, $row, 'adresse', 100);

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(50))
          $this->validateLengthShorterThanOrEqual($table, $row, 'land', 50);

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(120))
          $this->validateLengthShorterThanOrEqual($table, $row, 'koeberEmail', 120);

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(120))
          $this->validateLengthShorterThanOrEqual($table, $row, 'navn1', 120);

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(120))
          $this->validateLengthShorterThanOrEqual($table, $row, 'adresse1', 120);

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(50))
          $this->validateLengthShorterThanOrEqual($table, $row, 'land1', 50);

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(120))
          $this->validateLengthShorterThanOrEqual($table, $row, 'medKoeberEmail', 120);
        }


        // Salgshistorik

        if ($table == 'Salgshistorik') {
          if (!$this->validateIdExists($data['Grund'], $row['grundId'])) {
            $this->setForeignKeyNull($table, $row, 'grundId', NULL);
          }
          if (!$this->validateIdExists($data['PostBy'], $row['koeberPostById'])) {
            $this->setForeignKeyNull($table, $row, 'koeberPostById', NULL);
          }
          if (!$this->validateIdExists($data['PostBy'], $row['medKoeberPostById'])) {
            $this->setForeignKeyNull($table, $row, 'medKoeberPostById', NULL);
          }

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(30))
          $this->validateLengthShorterThanOrEqual($table, $row, 'salgsType', 30);

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(50))
          $this->validateLengthShorterThanOrEqual($table, $row, 'status', 50);

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(255))
          $this->validateLengthShorterThanOrEqual($table, $row, 'navn', 255);

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(100))
          $this->validateLengthShorterThanOrEqual($table, $row, 'adresse', 100);

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(50))
          $this->validateLengthShorterThanOrEqual($table, $row, 'land', 50);

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(120))
          $this->validateLengthShorterThanOrEqual($table, $row, 'koeberEmail', 120);

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(120))
          $this->validateLengthShorterThanOrEqual($table, $row, 'navn1', 120);

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(120))
          $this->validateLengthShorterThanOrEqual($table, $row, 'adresse1', 120);

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(50))
          $this->validateLengthShorterThanOrEqual($table, $row, 'land1', 50);

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(120))
          $this->validateLengthShorterThanOrEqual($table, $row, 'medKoeberEmail', 120);
        }


        // Opkoeb

        if ($table == 'Opkoeb') {
          if (!$this->validateIdExists($data['Lokalplan'], $row['lpId'])) {
            $this->setForeignKeyNull($table, $row, 'lpId', NULL);
          }

          // Ensure that "m2" is either null or numeric for safe column type conversion (varchar(50) -> INT)
          $this->convertToNumeric($table, $row, 'm2');

          // Ensure that "pris" is either null or numeric for safe column type conversion (varchar(50) -> INT)
          $this->convertToNumeric($table, $row, 'pris');

          // Ensure that "procentAfLP" is either null or numeric for safe column type conversion (varchar(50) -> INT)
          $this->convertToNumeric($table, $row, 'procentAfLP');

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(120))
          $this->validateLengthShorterThanOrEqual($table, $row, 'ejerlav', 60);
        }


        // Interessent

        if ($table == 'Interessent') {
          if (!$this->validateIdExists($data['PostBy'], $row['koeberPostById'])) {
            $this->setForeignKeyNull($table, $row, 'koeberPostById', NULL);
          }
          if (!$this->validateIdExists($data['PostBy'], $row['medKoeberPostById'])) {
            $this->setForeignKeyNull($table, $row, 'medKoeberPostById', NULL);
          }

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(255))
          $this->validateLengthShorterThanOrEqual($table, $row, 'navn', 255);

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(100))
          $this->validateLengthShorterThanOrEqual($table, $row, 'adresse', 100);

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(50))
          $this->validateLengthShorterThanOrEqual($table, $row, 'land', 50);

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(120))
          $this->validateLengthShorterThanOrEqual($table, $row, 'koeberEmail', 120);

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(120))
          $this->validateLengthShorterThanOrEqual($table, $row, 'navn1', 120);

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(120))
          $this->validateLengthShorterThanOrEqual($table, $row, 'adresse1', 120);

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(50))
          $this->validateLengthShorterThanOrEqual($table, $row, 'land1', 50);

          // Ensure field doesn't exceed safe maxlength for safe column type conversion (LONGTEXT -> VARCHAR(120))
          $this->validateLengthShorterThanOrEqual($table, $row, 'medKoeberEmail', 120);
        }


        // InteressentGrundMapping

        if ($table == 'InteressentGrundMapping') {
          if (!$this->validateIdExists($data['Grund'], $row['grundId'])) {
            $this->setForeignKeyNull($table, $row, 'grundId', NULL);
          }

          // Warn if either mapping id is empty - redundant row will be deleted
          if (empty($row['grundId'])) {
            $this->printWarning($table, $row, 'grundId', 'Row redundant if referenced id NULL - row will be deleted');
          } else if (empty($row['interessentId'])) {
            $this->printWarning($table, $row, 'interessentId', 'Row redundant if referenced id NULL - row will be deleted');
          }

          // Ensure that "annulleret" is either null or 0/1 for safe column type conversion (varchar(50) -> BOOL)
          $this->convertXToBoolean($table, $row, 'annulleret');
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
    $value = ($value === '') ? NULL : $value;

    if ($row[$column] !== $value) {
      $this->printWarning($table, $row, $column, $value);

      $this->countChanges($table, $column, $value, $this->changeCount);

      $row[$column] = $value;
    }
  }

  /**
   * Clear foreign key
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
  private function setForeignKeyNull(string $table, array &$row, string $column, $value)
  {
    $this->printWarning($table, $row, $column, $value, 'foreign key nulled');

    $this->countChanges($table, $column, $value, $this->changeForeignKeyCount);

    $row[$column] = $value;
  }

  /**
   * Count number of changes by table/column/value
   *
   * @param string $table
   * @param string $column
   * @param $value
   */
  private function countChanges(string $table, string $column, $value, &$changeCount)
  {

    $value = $value === NULL ? 'NULL_NULL' : $value;

    if (!is_array($changeCount)) {
      $changeCount = array();
    }

    if (!array_key_exists($table, $changeCount)) {
      $changeCount[$table] = array();
    }

    if (!array_key_exists($column, $changeCount[$table])) {
      $changeCount[$table][$column] = array();
    }

    if (!array_key_exists($value, $changeCount[$table][$column])) {
      $changeCount[$table][$column][$value] = 0;
    }

    $changeCount[$table][$column][$value]++;

  }

  /**
   * Print formatted summary of changes
   */
  private function printChangeLog($changeCount)
  {

    $output = $this->output instanceof ConsoleOutputInterface ? $this->output->getErrorOutput() : $this->output;
    $output->writeln('<comment>Warning: Changes made to the following tables</comment>');

    foreach ($changeCount as $tName => $table) {
      $output->writeln(sprintf('<comment>Table: %s</comment>', $tName));

      foreach ($table as $cName => $column) {
        $output->writeln(sprintf('<comment>- Column: %s</comment>', $cName));

        foreach ($column as $value => $count) {
          $value = $value === 'NULL_NULL' ? NULL : $value;

          $output->writeln(sprintf('<comment>-- %s rows set to %s</comment>', $count, var_export($value, TRUE)));
        }
      }
    }
  }


  /**
   * Convert value in import row to numeric value.
   *
   * @param string $table
   *   The table name.
   * @param array $row
   *   The row.
   * @param string $column
   *   The column name.
   */
  private function convertToNumeric(string $table, array &$row, string $column)
  {
    if (!is_numeric($row[$column])) {
      if (is_numeric(trim($row[$column]))) {
        $this->setValue($table, $row, $column, trim($row[$column]));
      } else if (empty($row[$column])) {
        $this->setValue($table, $row, $column, NULL);
      } else {
        $this->throwException($table, $row, $column, 'Cannot be safely converted to nummeric value');
      }
    }
  }

  /**
   * Convert value in import row to boolean value.
   *
   * @param string $table
   *   The table name.
   * @param array $row
   *   The row.
   * @param string $column
   *   The column name.
   */
  private function convertXToBoolean(string $table, array &$row, string $column)
  {
    if ($row[$column] === 'X') {
      $this->setValue($table, $row, $column, 1);
    } else if (empty($row[$column])) {
      $this->setValue($table, $row, $column, 0);
    } else {
      $this->throwException($table, $row, $column, 'Cannot be safely converted to bool value');
    }
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
   * @param string $message
   *   The warning message.
   */
  private function printWarning(string $table, array &$row, string $column, $value, $message = null)
  {
    $output = $this->output instanceof ConsoleOutputInterface ? $this->output->getErrorOutput() : $this->output;

    $output->writeln(sprintf('<comment>Warning: %s#%d.%s: %s -> %s %s</comment>', $table, $row['id'], $column, var_export($row[$column], TRUE), var_export($value, TRUE), $message));
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
  private function validateIdExists(array &$table, $id)
  {
    foreach ($table as $row) {
      if ($row['id'] === $id) {
        return true;
      }
    }

    return false;
  }

  private function validateLengthShorterThanOrEqual(string $table, array &$row, string $column, $maxLength)
  {
    if (!empty($row[$column]) && !strval($row[$column])) {
      $this->throwException($table, $row, $column, 'is not a valid string');
    }

    $value = trim($row[$column]);
    if (strlen($value) > $maxLength) {
      $this->throwException($table, $row, $column, 'is longer than ' . $maxLength);
    }

    $this->setValue($table, $row, $column, $value);
  }

}
