<?php

namespace Application\Migrations;

use AppBundle\Entity\User;
use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Migration:
 * - Alter fields for better match between datatype <> columntype
 */
class Version00000000000006 extends AbstractMigration
{
  /**
   * @param Schema $schema
   */
  public function up(Schema $schema)
  {
    // this up() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    // long-text to int conversion - assumes only safe values in DB
    // "import-legacy-data" command throws exception for non-safe values
    $this->addSql('ALTER TABLE Grund CHANGE husNummer husNummer INT DEFAULT NULL');

    // varchar to bool/tinyint conversion - assumes only safe values in DB
    // "import-legacy-data" command throws exception for non-safe values
    $this->addSql('ALTER TABLE Grund CHANGE annonceresEj annonceresEj BOOLEAN DEFAULT NULL');

    // varchar to bool/tinyint conversion - assumes only safe values in DB
    // "import-legacy-data" command throws exception for non-safe values
    $this->addSql('ALTER TABLE InteressentGrundMapping CHANGE annulleret annulleret BOOLEAN NOT NULL');

    // int(11) to bool/tinyint conversion - assumes only safe values in DB
    // "import-legacy-data" command throws exception for non-safe values
    $this->addSql('ALTER TABLE Landinspektoer CHANGE active active BOOLEAN NOT NULL');

    // int(11) to bool/tinyint conversion - assumes only safe values in DB
    // "import-legacy-data" command throws exception for non-safe values
    $this->addSql('ALTER TABLE Lokalsamfund CHANGE active active BOOLEAN NOT NULL');

    // varchar to int conversion - assumes only safe values in DB
    // "import-legacy-data" command throws exception for non-safe values
    $this->addSql('ALTER TABLE Opkoeb CHANGE m2 m2 INT DEFAULT NULL');

    // varchar to int conversion - assumes only safe values in DB
    // "import-legacy-data" command throws exception for non-safe values
    $this->addSql('ALTER TABLE Opkoeb CHANGE pris pris DOUBLE PRECISION DEFAULT NULL');

    // varchar to int conversion - assumes only safe values in DB
    // "import-legacy-data" command throws exception for non-safe values
    $this->addSql('ALTER TABLE Opkoeb CHANGE procentAfLP procentAfLP DOUBLE PRECISION DEFAULT NULL');


  }

  /**
   * @param Schema $schema
   */
  public function down(Schema $schema)
  {
    // this down() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

  }

}
