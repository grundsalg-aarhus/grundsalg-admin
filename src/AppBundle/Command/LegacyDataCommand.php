<?php

namespace AppBundle\Command;

use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LegacyDataCommand extends ContainerAwareCommand {
  protected function configure() {
    $this
      ->setName('app:import-legacy-data')
      ->setDescription('Imports legacy data.')
      ->addArgument('file', InputArgument::REQUIRED, 'The path to the json data dump.');
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
    $filename = $input->getArgument('file');
    $data = $this->getData($filename);

    if ($data) {
      $connection = $this->getContainer()->get('doctrine.dbal.default_connection');
      foreach ($data as $table => $rows) {
        $output->writeln(sprintf('Table: %s (#rows: %d)', $table, count($rows)));

        foreach ($rows as $row) {
          $columns = array_keys($row);
          $sql = 'INSERT INTO ' . $table . ' (' . implode(', ', $columns);
          $sql .= ') VALUES (' . implode(', ', array_map(function ($column) { return ':' . $column; }, $columns));
          $sql .= ');';
          // echo $sql, PHP_EOL;
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

  private function getData($filename) {
    if (!file_exists($filename)) {
      throw new \Exception('File ' . $filename . ' does not exist');
    }

    $data = @json_decode(file_get_contents($filename), TRUE);

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

		// Normalize data.

		foreach ($data as $table => &$rows) {
			foreach ($rows as &$row) {
				if ($table == 'Delomraade') {
					if (in_array($row['lokalplanId'], ['LP. 763', 'Areal ved Skovvejen, Aarhus N'], true)) {
						$row['lokalplanId'] = null;
					}
				}

				if ($table == 'Salgsomraade') {
					if (in_array($row['landinspektorId'], ['', '0'], true)) {
						$row['landinspektorId'] = null;
					}
					if (in_array($row['delomraadeId'], ['', '0', '1020-TEST'], true)) {
						$row['delomraadeId'] = null;
					}
					if (in_array($row['postById'], ['0'], true)) {
						$row['postById'] = null;
					}
					if (in_array($row['lokalPlanId'], ['0'], true)) {
						$row['lokalPlanId'] = null;
					}
				}

				if ($table == 'Grund') {
					if (in_array($row['medKoeberPostById'], ['', '0', '239'], true)) {
						$row['medKoeberPostById'] = null;
					}
					if (in_array($row['koeberPostById'], ['', '0', '239'], true)) {
						$row['koeberPostById'] = null;
					}

					if (in_array($row['salgsomraadeId'], ['0', '15', '268', '271'])) {
						$row['salgsomraadeId'] = null;
					}
				}

				if ($table == 'Salgshistorik') {
					if (in_array($row['grundId'], ['0'], true)) {
						$row['grundId'] = null;
					}
					if (in_array($row['grundId'], ['96', '115', '1299', '1300', '1745', '1746', '1753', '1757', '1769', '1770', '1742', '1771', '1748', '1666', '1654'], true)) {
						$row['grundId'] = null;
					}
					if (in_array($row['koeberPostById'], ['', '0', '239'], true)) {
						$row['koeberPostById'] = null;
					}
					if (in_array($row['medKoeberPostById'], ['', '0'], true)) {
						$row['medKoeberPostById'] = null;
					}
				}

				if ($table == 'Opkoeb') {
					if (in_array($row['lpId'], ['0'], true)) {
						$row['lpId'] = null;
					}
				}

				if ($table == 'Interessent') {
					if (in_array($row['koeberPostById'], ['', '0'], true)) {
						$row['koeberPostById'] = null;
					}
					if (in_array($row['medKoeberPostById'], ['', '0'], true)) {
						$row['medKoeberPostById'] = null;
					}
				}

				if ($table == 'InteressentGrundMapping') {
					if (in_array($row['grundId'], ['0', '115', '1746', '1747', '1748', '1749', '1753', '1757', '1758', '1759', '1760', '1770', '1772', '1773', '2427', '2427'], true)) {
						$row['grundId'] = null;
					}
				}
			}
		}

    return $data;
  }
}
