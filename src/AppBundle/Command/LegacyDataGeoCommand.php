<?php

namespace AppBundle\Command;

use AppBundle\Entity\Lokalplan;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use GuzzleHttp\Exception\ClientException;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use GuzzleHttp\Client;

/**
 * Legacy data command.
 */
class LegacyDataGeoCommand extends ContainerAwareCommand
{

  /**
   * {@inheritdoc}
   */
  protected function configure()
  {
    $this
      ->setName('app:import-legacy-data-geo')
      ->setDescription('Imports legacy Geo data.')
      ->addArgument('file', InputArgument::REQUIRED, 'The path to the json data dump.');
  }

  private $output;
  private $changeCount;
  private $newRoads = array();

  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $this->output = $output;

    $output->writeln('------------------');
    $output->writeln('Starting geo/web import');

    $filename = $input->getArgument('file');
    $data = $this->getData($filename);

    if ($data) {
      $connection = $this->getContainer()->get('doctrine.dbal.default_connection');
      foreach ($data as $table => $rows) {
        $output->writeln(sprintf('%s (#rows: %d)', $table, count($rows)));

        foreach ($rows as $row) {

          if (isset($row['ID']) && $row['ID'] !== 0) {
            if (strpos($table, 'Grunde') !== false) {
              $fagsystemID = $this->matchToGrund($table, $row['Adresse'], $row['Pris'], $row['Grundpris'], $row['m2'], $row['Status']);
              $pdflink = $this->getUrlFromString($table, $row['Adresse_link']);

              if ($fagsystemID) {
                try {
                  $sql = 'UPDATE Grund SET SP_GEOMETRY = ST_GEOMFROMTEXT(?, ?), srid = ?, MI_STYLE = ?, pdflink = ? WHERE id = ?';

                  $stmt = $connection->prepare($sql);
                  $stmt->bindValue(1, $row['WKT']);
                  $stmt->bindValue(2, $row['srid']);
                  $stmt->bindValue(3, $row['MI_STYLE']);
                  $stmt->bindValue(4, $row['srid']);
                  $stmt->bindValue(5, $pdflink);
                  $stmt->bindValue(6, $fagsystemID);
                  $stmt->execute();

                } catch (ForeignKeyConstraintViolationException $e) {
                  throw $e;
                }
              }

            }

            if (strpos($table, 'Omraader') !== false) {

              // If we know the match between web<>fagsystem then use that, else best guess.
              $fagsystemID = $this->matchToOmraadeStatic($table, $row['ID']);
              if(!$fagsystemID) {
                $fagsystemID = $this->matchToOmraade($table, $row['OmraadeNavn'], $row['Info1_overskr']);
              }

              if($fagsystemID) {

                $fagsystemID =  is_array($fagsystemID) ? $fagsystemID : array($fagsystemID);

                foreach ($fagsystemID as $id) {
                  try {
                    $sql = 'UPDATE Salgsomraade SET SP_GEOMETRY = ST_GEOMFROMTEXT(?, ?), srid = ?, MI_STYLE = ? WHERE id = ?';

                    $stmt = $connection->prepare($sql);
                    $stmt->bindValue(1, $row['WKT']);
                    $stmt->bindValue(2, $row['srid']);
                    $stmt->bindValue(3, $row['srid']);
                    $stmt->bindValue(4, $row['MI_STYLE']);
                    $stmt->bindValue(5, $id);
                    $stmt->execute();

                  } catch (ForeignKeyConstraintViolationException $e) {
                    throw $e;
                  }
                }
              }

            }

          }
        }
      }
    }

    $output->writeln('Import summary');
    $this->printChangeLog($this->changeCount);

  }

  /**
   * Match "Web" Salgsomrader to "Fag" Salgsomraader from staic tables of matches.
   * These are the areas we hjave confirmed as matched.
   *
   * @param $table
   * @param $id
   * @return bool|mixed
   */
  private function matchToOmraadeStatic($table, $id) {

    $parcelhusgrundMatchArray = [
      711 => array(223),
      780 => array(112),
      854 => array(170),
      768 => array(114),
      818 => array(272),
      994 => array(276),
      823 => array(141)
    ];

    $storparcelMatchArray = [
      763 => array(32),
      768 => array(1),
      878 => array(40)
    ];

    $erhvervsgrundMatchArray = [
      27 => array(7),
      374 => array(25, 26),
      868 => array(66),
      13601 => array(2),
      812 => array(64),
      802 => array(61, 62),
      602 => array(49),
      428 => array(39, 40),
      846 => array(66, 67, 68),
      411 => array(31, 32, 33, 34, 35, 36, 37),
      59 => array(48),
      359 => array(20, 21),
      692 => array(55),
      546 => array(47)
    ];

    switch ($table) {
      case 'OmraaderBolig':
        return array_key_exists($id, $parcelhusgrundMatchArray) ? $parcelhusgrundMatchArray[$id] : FALSE;

      case 'OmraaderErhverv':
        return array_key_exists($id, $erhvervsgrundMatchArray) ? $erhvervsgrundMatchArray[$id] : FALSE;

      case 'OmraaderStorParcel':
        return array_key_exists($id, $storparcelMatchArray) ? $storparcelMatchArray[$id] : FALSE;

      default:
        return FALSE;
    }
  }


  private function matchToOmraade($table, $OmraadeNavn, $info1_overskr)
  {
    $lokalplanNumber = $this->getNumberFromString($table, $OmraadeNavn, $info1_overskr);

    $connection = $this->getContainer()->get('doctrine.dbal.default_connection');

    $sql = 'SELECT s.id, lp.nr FROM Salgsomraade s LEFT JOIN Lokalplan lp ON s.lokalplanId = lp.id WHERE lp.nr = ?';

    try {
      $stmt = $connection->prepare($sql);
      $stmt->bindValue(1, $lokalplanNumber);
      $stmt->execute();

      $result = $stmt->fetchAll();
    } catch (ForeignKeyConstraintViolationException $e) {
      throw $e;
    }

    $c = count($result);
    if (count($result) === 1) {
      $this->countChanges($table, 'Omraade matched', $this->changeCount);
      return $result[0]['id'];
    } else if ($c === 0) {
      $this->countChanges($table, 'Omraade ZERO matches', $this->changeCount);
      $this->printWarning('Omraade ZERO matches: ' . $table . ' / ' . $OmraadeNavn . ', Lokalplan: ' . $info1_overskr . ' matches ' . $c . ' rows');
    } else {
      $this->countChanges($table, 'Omraade MULTIPLE matches', $this->changeCount);
      $this->printWarning('Omraade MULTIPLE matches: ' . $table . ' / ' . $OmraadeNavn . ', Lokalplan: ' . $info1_overskr . ' matches ' . $c . ' rows');
    }

    return false;
  }

  private function matchToGrund($table, $adresse, $pris, $grundPris, $m2, $status)
  {
    $connection = $this->getContainer()->get('doctrine.dbal.default_connection');

    $husnummer = $this->getNumberFromString($table, '', $adresse);
    $grundType = $this->getGrundtypeFromTableName($table);
    $vej = $this->getVejnavn($adresse);
    $bogstav = $this->getBogstav($adresse, $husnummer);

    try {
      $sql = 'SELECT * FROM Grund WHERE vej LIKE ? AND husNummer = ? AND (grundType = ? OR grundType = ?)';
      $stmt = $connection->prepare($sql);
      $stmt->bindValue(1, $vej. '%');
      $stmt->bindValue(2, $husnummer);
      $stmt->bindValue(3, $grundType);
      $stmt->bindValue(4, 'Andre');
      $stmt->execute();

      $result = $stmt->fetchAll();
    } catch (ForeignKeyConstraintViolationException $e) {
      throw $e;
    }

    $c = count($result);

    // Try to match by bogstav if multiple rows found
    if ($c > 1 && $bogstav !== null) {
      $found = array();
      foreach ($result as $key => $item) {
        if ($bogstav === $item['bogstav']) {
          $found[] = $key;
        }
      }

      if (count($found) === 1) {
        $result = array($result[$found[0]]);
      }

    }

    // Try secondary strategy if more/less than one result found
    $c = count($result);
    if($c < 1) {

      try {
        $sql = 'SELECT * FROM Grund WHERE (bruttoAreal = ? OR maxEtageM2 = ?) AND pris = ? AND vej LIKE ? AND (grundType = ? OR grundType = ?)';
        $stmt = $connection->prepare($sql);
        $stmt->bindValue(1, $m2);
        $stmt->bindValue(2, $m2);
        $stmt->bindValue(3, $pris);
        $stmt->bindValue(4, $vej. '%');
        $stmt->bindValue(5, $grundType);
        $stmt->bindValue(6, 'Andre');
        $stmt->execute();

        $result = $stmt->fetchAll();
      } catch (ForeignKeyConstraintViolationException $e) {
        throw $e;
      }

      $c = count($result);

      // Try to match by husnummer if multiple rows found
      if ($c > 1) {
        $found = array();
        foreach ($result as $key => $item) {
          if ($husnummer === $item['husNummer']) {
            $found[] = $key;
          }
        }

        if (count($found) === 1) {
          $result = array($result[$found[0]]);
        }

      }
    }

    $c = count($result);
    if($c === 0 && !$this->validateVejExistInFagsystem($vej)) {
      $this->countChanges($table, 'NEW vej, Grund INSERTED', $this->changeCount);
      $this->printWarning('NEW vej, Grund INSERTED: ' . $table . ' / ' . $adresse . ', m2: ' . $m2 . ', pris: ' . $pris . ', gPris: ' . $grundPris . ' matches ' . $c . ' rows');
      return $this->insertGrund($vej, $husnummer, $bogstav, $grundType, $m2, $status);
    } else if ($c === 1) {
      $this->countChanges($table, 'Grund matched', $this->changeCount);
      return $result[0]['id'];
    } else if($c === 0) {
      $this->countChanges($table, 'Grund ZERO matches', $this->changeCount);
      $this->printWarning('Grund ZERO matches: ' . $table . ' / ' . $adresse . ', m2: ' . $m2 . ', pris: ' . $pris . ', gPris: ' . $grundPris . ' matches ' . $c . ' rows');
    } else {
      $this->countChanges($table, 'Grund MULTIPLE matches', $this->changeCount);
      $this->printWarning('Grund MULTIPLE matches: ' . $table . ' / ' . $adresse . ', m2: ' . $m2 . ', pris: ' . $pris . ', gPris: ' . $grundPris . ' matches ' . $c . ' rows');
    }

    return false;

  }

  private function insertGrund($vej, $husnummer, $bogstav, $grundType, $m2, $status) {
    $connection = $this->getContainer()->get('doctrine.dbal.default_connection');

    try {
      $sql = 'INSERT INTO Grund (vej, husNummer, bogstav, grundType, areal, status, annonceresEj, createdDate, modifiedDate, createdBy, modifiedBy) VALUES (?, ? ,?, ?, ?, ?, ?, NOW(), NOW(), ?, ?)';
      $stmt = $connection->prepare($sql);
      $stmt->bindValue(1, $vej);
      $stmt->bindValue(2, $husnummer);
      $stmt->bindValue(3, $bogstav);
      $stmt->bindValue(4, $grundType);
      $stmt->bindValue(5, $m2);
      $stmt->bindValue(6, $status);
      $stmt->bindValue(7, true);
      $stmt->bindValue(8, 'Geo/Web import');
      $stmt->bindValue(9, 'Geo/Web import');
      $stmt->execute();

      $id = $connection->lastInsertId();
    } catch (ForeignKeyConstraintViolationException $e) {
      throw $e;
    }

    if(!in_array($vej, $this->newRoads)) {
      $this->newRoads[] = $vej;
    }

    return $id;
  }

  private function getVejnavn($adresse) {
    $adresse = str_replace('_', ' ', $adresse);
    $adresseParts = explode(' ', $adresse);
    $vej = preg_replace('/,$/', '', $adresseParts[0]);

    if(($vej === 'Ved' || $vej === 'Gammel' || $vej === 'Ny') && isset($adresseParts[1])) {
      $vej .= ' '.$adresseParts[1];
    }

    return $vej;
  }

  private function validateVejExistInFagsystem($vej) {
    $connection = $this->getContainer()->get('doctrine.dbal.default_connection');

    // If road has been added by this script return false
    if(in_array($vej, $this->newRoads)) {
      return false;
    }

    try {
      $sql = 'SELECT * FROM Grund WHERE vej LIKE ?';
      $stmt = $connection->prepare($sql);
      $stmt->bindValue(1, $vej. '%');
      $stmt->execute();

      $result = $stmt->fetchAll();
    } catch (ForeignKeyConstraintViolationException $e) {
      throw $e;
    }

    if(count($result) > 0) {
      return true;
    }

    return false;
  }

  private function getGrundtypeFromTableName($table) {
    switch ($table) {
      case 'GrundeBolig':
        return 'Parcelhusgrund';
        break;
      case 'GrundeStorParcel':
        return 'Storparcel';
        break;
      case 'GrundeErhverv':
        return 'Erhvervsgrund';
        break;
      default:
        throw new \Exception('Error: $table cannot be mapped');
    }
  }

  private function getNumberFromString($table, $OmraadeNavn, $str)
  {
    if (!empty(trim($str))) {
      $regex = '/\d+/';
      preg_match_all($regex, $str, $matches);

      if ($matches[0]) {
        if ($matches[0][0]) {
          return $matches[0][0];
        }
      }

      $this->countChanges($table, 'String is non-empty, but no number found', $this->changeCount);
      $this->printWarning('Warning: ' . $table . ' / ' . $OmraadeNavn . ' - String: "' . $str . '" is non-empty, but no number found!');
    }

    return null;
  }

  private function getBogstav($adresse, $husnummer) {
    $adresse = str_replace('_', ' ', $adresse);
    $adresseParts = explode(' ', $adresse);
    $bogstav = $adresseParts[count($adresseParts) -1];

    if($bogstav !== $husnummer && strlen($bogstav) < 4) {
      $bogstav = str_replace($husnummer, '', $bogstav);
      $bogstav = strtoupper($bogstav);

      return $bogstav;
    }

    return null;
  }

  private function getUrlFromString($table, $str)
  {
    if (!empty(trim($str))) {
      $regex = '/href=([\'"])(.*?)\1/';
      preg_match_all($regex, $str, $matches);

      if ($matches[2]) {
        if ($matches[2][0]) {
          $url = $matches[2][0];

          // Remove periods from end of URL
          // E.g correct <a  href='http://gis.aarhus.dk/grundsalg/doc/Villaparceller/Lp. 768 Trige/Baermosehojen 113.pdf.' target=....
          if (substr($url, -1) === '.') {
            $url = substr($url, 0, -1);
          }

          return $this->validateURL($table, $url) ? $url : null;
        }
      }

      $this->countChanges($table, 'String is non-empty, but no URL found', $this->changeCount);
      $this->printWarning('String: "' . $str . '" is non-empty, but no URL found!');
    }

    return null;
  }

  private function validateURL($table, $url)
  {
    if (empty($url)) {
      throw new \Exception('Error: $url cannot be empty');
    } else if (strlen($url > 255)) {
      throw new \Exception('Error: $url cannot be longer than 255');
    }

    $client = new Client();

    try {
      $client->request('GET', $url);
    } catch (ClientException $e) {
      if ($e->getCode() === 404) {
        $this->countChanges($table, '404 - URL not found', $this->changeCount);
        $this->printWarning('404 - URL not found: ' . $url);

        return false;
      } else {
        throw $e;
      }
    }


    return true;
  }

  /**
   * Set value in import row.
   * @param string $message
   *   The warning message.
   */
  private function printWarning($message)
  {
    $output = $this->output instanceof ConsoleOutputInterface ? $this->output->getErrorOutput() : $this->output;

    $output->writeln(sprintf('<comment>Warning: %s</comment>', $message));
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

    // URL changed, but doesn't forward (july, 2017)
    $content = str_ireplace('gis.aarhus.dk', 'webgis.aarhus.dk', $content);

    $data = @json_decode($content, TRUE);

    if (!$data) {
      throw new \Exception('No data read');
    }

    return $data;
  }

  /**
   * Count number of changes by table/column/value
   *
   * @param string $table
   * @param string $column
   * @param $value
   */
  private function countChanges(string $table, $value, &$changeCount)
  {

    $value = $value === NULL ? 'NULL_NULL' : $value;

    if (!is_array($changeCount)) {
      $changeCount = array();
    }

    if (!array_key_exists($table, $changeCount)) {
      $changeCount[$table] = array();
    }

    if (!array_key_exists($value, $changeCount[$table])) {
      $changeCount[$table][$value] = 0;
    }

    $changeCount[$table][$value]++;

  }

  /**
   * Print formatted summary of changes
   */
  private function printChangeLog($changeCount)
  {

    $output = $this->output instanceof ConsoleOutputInterface ? $this->output->getErrorOutput() : $this->output;
    $output->writeln('<comment>Warning: Import potentially incomplete</comment>');

    foreach ($changeCount as $tName => $table) {
      $output->writeln(sprintf('<comment>Table: %s</comment>', $tName));

      foreach ($table as $value => $count) {
        $value = $value === 'NULL_NULL' ? NULL : $value;

        $output->writeln(sprintf('<comment>-- %s %s</comment>', $count, $value));
      }
    }

  }

}
