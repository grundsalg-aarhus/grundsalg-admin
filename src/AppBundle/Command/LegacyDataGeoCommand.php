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
    private $newRoads = [];

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;

        $output->writeln('------------------');
        $output->writeln('Starting geo/web import');

        $filename = $input->getArgument('file');
        $data     = $this->getData($filename);

        if ($data) {
            $connection = $this->getContainer()->get('doctrine.dbal.default_connection');
            foreach ($data as $table => $rows) {
                $output->writeln(sprintf('%s (#rows: %d)', $table, count($rows)));

                foreach ($rows as $row) {

                    if (isset($row['ID']) && $row['ID'] !== 0) {

                        // GRUNDE
                        if (strpos($table, 'Grunde') !== false) {
                            // If we know the match between web<>fagsystem then use that, else best guess.
                            $fagsystemID = $this->matchToGrundStatic($table, $row['ID']);
                            if ( ! $fagsystemID) {
                                $fagsystemID = $this->matchToGrund($table, $row);
                            }

                            $pdflink     = $this->getUrlFromString($table, $row);

                            if ($fagsystemID) {

                                $fagsystemID = is_array($fagsystemID) ? $fagsystemID : [$fagsystemID];

                                foreach ($fagsystemID as $id) {
                                    try {
                                        $sql = 'UPDATE Grund SET SP_GEOMETRY = ST_GEOMFROMTEXT(?, ?), srid = ?, MI_STYLE = ?, pdflink = ? WHERE id = ?';

                                        $stmt = $connection->prepare($sql);
                                        $stmt->bindValue(1, $row['WKT']);
                                        $stmt->bindValue(2, $row['srid']);
                                        $stmt->bindValue(3, $row['srid']);
                                        $stmt->bindValue(4, $row['MI_STYLE']);
                                        $stmt->bindValue(5, $pdflink);
                                        $stmt->bindValue(6, $id);
                                        $stmt->execute();

                                    } catch (ForeignKeyConstraintViolationException $e) {
                                        throw $e;
                                    }
                                }
                            }

                        }

                        // OMRAADER
                        if (strpos($table, 'Omraader') !== false) {

                            // If we know the match between web<>fagsystem then use that, else best guess.
                            $fagsystemID = $this->matchToOmraadeStatic($table, $row['ID']);
                            if ( ! $fagsystemID) {
                                $fagsystemID = $this->matchToOmraade($table, $row);
                            }

                            if ($fagsystemID) {

                                $fagsystemID = is_array($fagsystemID) ? $fagsystemID : [$fagsystemID];

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

    private function matchToGrundStatic($table, $id)
    {

        $erhvervMatchArray = [
            57  => [2428],
            85  => [278],
            141 => [1136],
            191 => [1152],
            191 => [1153],
            192 => [1153],
            193 => [1154],
            194 => [1155],
            195 => [1156],
            196 => [1157],
            197 => [1158],
            212 => [1557],
            213 => [1556],
            214 => [1555],
            215 => [1558],
            216 => [1559],
            217 => [1563],
            218 => [1562],
            219 => [1561],
            221 => [1560],
            227 => [1568],
            228 => [1569],
            229 => [1570],
            230 => [1571],
            231 => [1572],
            232 => [1573],
            233 => [1574],
            234 => [1575],
            235 => [1576],
            236 => [1577],
            250 => [1638],
        ];

        return array_key_exists($id, $erhvervMatchArray) ? $erhvervMatchArray[$id] : false;

    }

    /**
     * Match "Web" Salgsomrader to "Fag" Salgsomraader from static tables of matches.
     * These are the areas we have confirmed as matched.
     *
     * @param $table
     * @param $id
     *
     * @return bool|mixed
     */
    private function matchToOmraadeStatic($table, $id)
    {
        // WebID / FagsID(s)
        $boligMatchArray = [
            711 => [223],
            780 => [112],
            854 => [170],
            768 => [114],
            818 => [272],
            994 => [276],
            823 => [141],
        ];

        $storparcelMatchArray = [
            763 => [32],
            768 => [1],
            878 => [40],
        ];

        $erhvervMatchArray = [
            27    => [7],
            374   => [25, 26],
            868   => [66],
            13601 => [2],
            812   => [64],
            802   => [61, 62],
            602   => [49],
            428   => [39, 40],
            846   => [66, 67, 68],
            411   => [31, 32, 33, 34, 35, 36, 37],
            59    => [48],
            359   => [20, 21],
            692   => [55],
            546   => [47],
        ];

        switch ($table) {
            case 'OmraaderBolig':
                return array_key_exists($id, $boligMatchArray) ? $boligMatchArray[$id] : false;

            case 'OmraaderErhverv':
                return array_key_exists($id, $erhvervMatchArray) ? $erhvervMatchArray[$id] : false;

            case 'OmraaderStorParcel':
                return array_key_exists($id, $storparcelMatchArray) ? $storparcelMatchArray[$id] : false;

            case 'FremtidigeOmraaderBolig':
                return array_key_exists($id, $boligMatchArray) ? $boligMatchArray[$id] : false;

            case 'FremtidigeOmraaderErhverv':
                return array_key_exists($id, $erhvervMatchArray) ? $erhvervMatchArray[$id] : false;

            case 'FremtidigeOmraaderStorParcel':
                return array_key_exists($id, $storparcelMatchArray) ? $storparcelMatchArray[$id] : false;

            default:
                return false;
        }
    }

    private function getOmraadeType($table)
    {
        switch ($table) {
            case 'OmraaderBolig':
                return 'Parcelhusgrund';

            case 'OmraaderErhverv':
                return 'Erhvervsgrund';

            case 'OmraaderStorParcel':
                return 'Storparcel';

            case 'FremtidigeOmraaderBolig':
                return 'Parcelhusgrund';

            case 'FremtidigeOmraaderErhverv':
                return 'Erhvervsgrund';

            case 'FremtidigeOmraaderStorParcel':
                return 'Storparcel';

            default:
                return false;
        }
    }

    private function matchToOmraade($table, $row)
    {
        $omraadeNavn     = $row['OmraadeNavn'];
        $omraadeType     = $this->getOmraadeType($table);
        $info1_overskr   = $row['Info1_overskr'];
        $lokalplanNumber = $this->getNumberFromString($table, $row, $omraadeNavn, $info1_overskr);

        $connection = $this->getContainer()->get('doctrine.dbal.default_connection');

        $sql = 'SELECT s.id, s.type, lp.nr FROM Salgsomraade s LEFT JOIN Lokalplan lp ON s.lokalplanId = lp.id WHERE lp.nr = ? AND s.type = ?';

        try {
            $stmt = $connection->prepare($sql);
            $stmt->bindValue(1, $lokalplanNumber);
            $stmt->bindValue(2, $omraadeType);
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
            $this->printWarning($table, $row, 'Omraade ZERO matches: '.$table.' / '.$omraadeNavn.', Lokalplan: '.$info1_overskr.' matches '.$c.' rows');
        } else {
            $this->countChanges($table, 'Omraade MULTIPLE matches', $this->changeCount);
            $this->printWarning($table, $row, 'Omraade MULTIPLE matches: '.$table.' / '.$omraadeNavn.', Lokalplan: '.$info1_overskr.' matches '.$c.' rows');
        }

        return false;
    }

    private function matchToGrund($table, $row)
    {
        $adresse   = $row['Adresse'];
        $pris      = $row['Pris'];
        $grundPris = $row['Grundpris'];
        $m2        = $row['m2'];
        $status    = $row['Status'];

        $connection = $this->getContainer()->get('doctrine.dbal.default_connection');

        $husnummer = $this->getNumberFromString($table, $row, '', $adresse);
        $grundType = $this->getGrundtypeFromTableName($table);
        $vej       = $this->getVejnavn($adresse);
        $bogstav   = $this->getBogstav($adresse, $husnummer);

        try {
            $sql  = 'SELECT * FROM Grund WHERE vej LIKE ? AND husNummer = ? AND (grundType = ? OR grundType = ?)';
            $stmt = $connection->prepare($sql);
            $stmt->bindValue(1, $vej.'%');
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
            $found = [];
            foreach ($result as $key => $item) {
                if ($bogstav === $item['bogstav']) {
                    $found[] = $key;
                }
            }

            if (count($found) === 1) {
                $result = [$result[$found[0]]];
            }

        }

        // Try secondary strategy if more/less than one result found
        $c = count($result);
        if ($c < 1) {

            try {
                $sql  = 'SELECT * FROM Grund WHERE (bruttoAreal = ? OR maxEtageM2 = ?) AND pris = ? AND vej LIKE ? AND (grundType = ? OR grundType = ?)';
                $stmt = $connection->prepare($sql);
                $stmt->bindValue(1, $m2);
                $stmt->bindValue(2, $m2);
                $stmt->bindValue(3, $pris);
                $stmt->bindValue(4, $vej.'%');
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
                $found = [];
                foreach ($result as $key => $item) {
                    if ($husnummer === $item['husNummer']) {
                        $found[] = $key;
                    }
                }

                if (count($found) === 1) {
                    $result = [$result[$found[0]]];
                }

            }
        }

        $c = count($result);
        if ($c === 1) {
            $this->countChanges($table, 'Grund matched', $this->changeCount);

            return $result[0]['id'];
        } else if ($c === 0) {
            $this->countChanges($table, 'Grund ZERO matches', $this->changeCount);
            $this->printWarning($table, $row, 'Grund ZERO matches: '.$table.'#'.$row['ID'].' / '.$adresse.', m2: '.$m2.', pris: '.$pris.', gPris: '.$grundPris.' matches '.$c.' rows');
        } else {
            $this->countChanges($table, 'Grund MULTIPLE matches', $this->changeCount);
            $this->printWarning($table, $row, 'Grund MULTIPLE matches: '.$table.'#'.$row['ID'].' / '.$adresse.', m2: '.$m2.', pris: '.$pris.', gPris: '.$grundPris.' matches '.$c.' rows');
        }

        return false;

    }

    private function getVejnavn($adresse)
    {
        $adresse      = str_replace('_', ' ', $adresse);
        $adresseParts = explode(' ', $adresse);
        $vej          = preg_replace('/,$/', '', $adresseParts[0]);

        if (($vej === 'Ved' || $vej === 'Gammel' || $vej === 'Ny') && isset($adresseParts[1])) {
            $vej .= ' '.$adresseParts[1];
        }

        return $vej;
    }

    private function getGrundtypeFromTableName($table)
    {
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

    private function getNumberFromString($table, $row, $OmraadeNavn, $str)
    {
        if ( ! empty(trim($str))) {
            $regex = '/\d+/';
            preg_match_all($regex, $str, $matches);

            if ($matches[0]) {
                if ($matches[0][0]) {
                    return $matches[0][0];
                }
            }

            $this->countChanges($table, 'String is non-empty, but no number found', $this->changeCount);
            $this->printWarning($table, $row, 'Warning: '.$table.' / '.$OmraadeNavn.' - String: "'.$str.'" is non-empty, but no number found!');
        }

        return null;
    }

    private function getBogstav($adresse, $husnummer)
    {
        $adresse      = str_replace('_', ' ', $adresse);
        $adresseParts = explode(' ', $adresse);
        $bogstav      = $adresseParts[count($adresseParts) - 1];

        if ($bogstav !== $husnummer && strlen($bogstav) < 4) {
            $bogstav = str_replace($husnummer, '', $bogstav);
            $bogstav = strtoupper($bogstav);

            return $bogstav;
        }

        return null;
    }

    private function getUrlFromString($table, $row)
    {
        $str = $row['Adresse_link'];

        if ( ! empty(trim($str))) {
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

                    return $this->validateURL($table, $row, $url) ? $url : null;
                }
            }

            $this->countChanges($table, 'String is non-empty, but no URL found', $this->changeCount);
            $this->printWarning($table, $row, 'String: "'.$str.'" is non-empty, but no URL found!');
        }

        return null;
    }

    private function validateURL($table, $row, $url)
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
                $this->printWarning($table, $row, '404 - URL not found: '.$url);

                return false;
            } else {
                throw $e;
            }
        }


        return true;
    }

    /**
     * Set value in import row.
     *
     * @param string $message
     *   The warning message.
     */
    private function printWarning($table, $row, $message)
    {
        $output = $this->output instanceof ConsoleOutputInterface ? $this->output->getErrorOutput() : $this->output;

        $output->writeln(sprintf('<comment>Warning: %s#%d: %s</comment>', $table, $row['ID'], $message));
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
        } else if (file_exists($filename)) {
            $content = file_get_contents($filename);
        } else {
            throw new \Exception('File '.$filename.' does not exist');
        }

        // URL changed, but doesn't forward (july, 2017)
        $content = str_ireplace('gis.aarhus.dk', 'webgis.aarhus.dk', $content);

        $data = @json_decode($content, true);

        if ( ! $data) {
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

        $value = $value === null ? 'NULL_NULL' : $value;

        if ( ! is_array($changeCount)) {
            $changeCount = [];
        }

        if ( ! array_key_exists($table, $changeCount)) {
            $changeCount[$table] = [];
        }

        if ( ! array_key_exists($value, $changeCount[$table])) {
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
                $value = $value === 'NULL_NULL' ? null : $value;

                $output->writeln(sprintf('<comment>-- %s %s</comment>', $count, $value));
            }
        }

    }

}
