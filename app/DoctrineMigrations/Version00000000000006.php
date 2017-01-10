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

    //  All ALTER statements assumes only safe values in DB
    // "import-legacy-data" command throws exception for non-safe values

    // long-text to int conversion
    $this->addSql('ALTER TABLE Grund CHANGE husNummer husNummer INT DEFAULT NULL');

    // varchar to bool/tinyint conversion
    $this->addSql('ALTER TABLE Grund CHANGE annonceresEj annonceresEj BOOLEAN DEFAULT NULL');

    // varchar to bool/tinyint conversion
    $this->addSql('ALTER TABLE InteressentGrundMapping CHANGE annulleret annulleret BOOLEAN NOT NULL');

    // int(11) to bool/tinyint conversion
    $this->addSql('ALTER TABLE Landinspektoer CHANGE active active BOOLEAN NOT NULL');

    // int(11) to bool/tinyint conversion
    $this->addSql('ALTER TABLE Lokalsamfund CHANGE active active BOOLEAN NOT NULL');

    // varchar to int conversion
    $this->addSql('ALTER TABLE Opkoeb CHANGE m2 m2 INT DEFAULT NULL');

    // varchar to int conversion
    $this->addSql('ALTER TABLE Opkoeb CHANGE pris pris DOUBLE PRECISION DEFAULT NULL');

    // varchar to int conversion
    $this->addSql('ALTER TABLE Opkoeb CHANGE procentAfLP procentAfLP DOUBLE PRECISION DEFAULT NULL');

    // longtext to int conversion
    $this->addSql('ALTER TABLE Lokalplan CHANGE samletAreal samletAreal INT DEFAULT NULL');

    // longtext to int conversion
    $this->addSql('ALTER TABLE Lokalplan CHANGE salgbartAreal salgbartAreal INT DEFAULT NULL');

    // longtext to VARCHAR conversion
    $this->addSql('ALTER TABLE PostBy CHANGE city city VARCHAR(100) NOT NULL');

    // longtext to VARCHAR conversion
    $this->addSql('ALTER TABLE Lokalsamfund CHANGE name name VARCHAR(50) NOT NULL');

    // longtext to VARCHAR conversion
    $this->addSql('ALTER TABLE Lokalplan CHANGE titel titel VARCHAR(255) NOT NULL');
    // longtext to VARCHAR conversion
    $this->addSql('ALTER TABLE Lokalplan CHANGE projektLeder projektLeder VARCHAR(50) NOT NULL');
    // longtext to VARCHAR conversion
    $this->addSql('ALTER TABLE Lokalplan CHANGE telefon telefon VARCHAR(20) NOT NULL');
    // longtext to VARCHAR conversion
    $this->addSql('ALTER TABLE Lokalplan CHANGE lokalPlanLink lokalPlanLink VARCHAR(255) NOT NULL');
    // longtext to VARCHAR conversion
    $this->addSql('ALTER TABLE Lokalplan CHANGE nr nr VARCHAR(50) NOT NULL');

    // longtext to VARCHAR conversion
    $this->addSql('ALTER TABLE Delomraade CHANGE anvendelse anvendelse VARCHAR(50) NOT NULL');
    $this->addSql('ALTER TABLE Delomraade CHANGE mulighedFor mulighedFor VARCHAR(50) NOT NULL');

    // longtext to VARCHAR conversion
    $this->addSql('ALTER TABLE Landinspektoer CHANGE adresse adresse VARCHAR(50) NOT NULL');
    $this->addSql('ALTER TABLE Landinspektoer CHANGE email email VARCHAR(50) NOT NULL');
    $this->addSql('ALTER TABLE Landinspektoer CHANGE mobil mobil VARCHAR(20) NOT NULL');
    $this->addSql('ALTER TABLE Landinspektoer CHANGE navn navn VARCHAR(100) NOT NULL');
    $this->addSql('ALTER TABLE Landinspektoer CHANGE telefon telefon VARCHAR(20) NOT NULL');

    // longtext to VARCHAR conversion
    $this->addSql('ALTER TABLE Salgsomraade CHANGE titel titel VARCHAR(255) NOT NULL');
    $this->addSql('ALTER TABLE Salgsomraade CHANGE type type VARCHAR(30) NOT NULL');
    $this->addSql('ALTER TABLE Salgsomraade CHANGE matrikkel1 matrikkel1 VARCHAR(20) NOT NULL');
    $this->addSql('ALTER TABLE Salgsomraade CHANGE matrikkel2 matrikkel2 VARCHAR(20) NOT NULL');
    $this->addSql('ALTER TABLE Salgsomraade CHANGE ejerlav ejerlav VARCHAR(60) NOT NULL');
    $this->addSql('ALTER TABLE Salgsomraade CHANGE vej vej VARCHAR(60) NOT NULL');
    $this->addSql('ALTER TABLE Salgsomraade CHANGE gisUrl gisUrl VARCHAR(255) NOT NULL');
    $this->addSql('ALTER TABLE Salgsomraade CHANGE tilsluttet tilsluttet VARCHAR(50) NOT NULL');
    $this->addSql('ALTER TABLE Salgsomraade CHANGE sagsNr sagsNr VARCHAR(50) NOT NULL');

    // longtext to VARCHAR conversion
    $this->addSql('ALTER TABLE Grund CHANGE mnr mnr VARCHAR(20) DEFAULT NULL');
    $this->addSql('ALTER TABLE Grund CHANGE mnr2 mnr2 VARCHAR(20) DEFAULT NULL');
    $this->addSql('ALTER TABLE Grund CHANGE delAreal delAreal VARCHAR(60) DEFAULT NULL');
    $this->addSql('ALTER TABLE Grund CHANGE ejerlav ejerlav VARCHAR(60) DEFAULT NULL');
    $this->addSql('ALTER TABLE Grund CHANGE vej vej VARCHAR(60) DEFAULT NULL');
    $this->addSql('ALTER TABLE Grund CHANGE bogstav bogstav VARCHAR(30) DEFAULT NULL');
    $this->addSql('ALTER TABLE Grund CHANGE urlGIS urlGIS VARCHAR(255) DEFAULT NULL');
    $this->addSql('ALTER TABLE Grund CHANGE tilsluttet tilsluttet VARCHAR(50) DEFAULT NULL');
    $this->addSql('ALTER TABLE Grund CHANGE prisKorrektion1 prisKorrektion1 VARCHAR(30) DEFAULT NULL');
    $this->addSql('ALTER TABLE Grund CHANGE prisKorrektion2 prisKorrektion2 VARCHAR(30) DEFAULT NULL');
    $this->addSql('ALTER TABLE Grund CHANGE prisKorrektion3 prisKorrektion3 VARCHAR(30) DEFAULT NULL');
    $this->addSql('ALTER TABLE Grund CHANGE salgsType salgsType VARCHAR(30) DEFAULT NULL');
    $this->addSql('ALTER TABLE Grund CHANGE navn navn VARCHAR(255) DEFAULT NULL');
    $this->addSql('ALTER TABLE Grund CHANGE adresse adresse VARCHAR(100) DEFAULT NULL');
    $this->addSql('ALTER TABLE Grund CHANGE land land VARCHAR(50) DEFAULT NULL');
    $this->addSql('ALTER TABLE Grund CHANGE koeberEmail koeberEmail VARCHAR(120) DEFAULT NULL');
    $this->addSql('ALTER TABLE Grund CHANGE navn1 navn1 VARCHAR(120) DEFAULT NULL');
    $this->addSql('ALTER TABLE Grund CHANGE adresse1 adresse1 VARCHAR(120) DEFAULT NULL');
    $this->addSql('ALTER TABLE Grund CHANGE land1 land1 VARCHAR(50) DEFAULT NULL');
    $this->addSql('ALTER TABLE Grund CHANGE medKoeberEmail medKoeberEmail VARCHAR(120) DEFAULT NULL');

    // longtext to VARCHAR conversion
    $this->addSql('ALTER TABLE Salgshistorik CHANGE salgsType salgsType VARCHAR(30) DEFAULT NULL');
    $this->addSql('ALTER TABLE Salgshistorik CHANGE status status VARCHAR(50) DEFAULT NULL');
    $this->addSql('ALTER TABLE Salgshistorik CHANGE navn navn VARCHAR(255) DEFAULT NULL');
    $this->addSql('ALTER TABLE Salgshistorik CHANGE adresse adresse VARCHAR(100) DEFAULT NULL');
    $this->addSql('ALTER TABLE Salgshistorik CHANGE land land VARCHAR(50) DEFAULT NULL');
    $this->addSql('ALTER TABLE Salgshistorik CHANGE koeberEmail koeberEmail VARCHAR(120) DEFAULT NULL');
    $this->addSql('ALTER TABLE Salgshistorik CHANGE navn1 navn1 VARCHAR(120) DEFAULT NULL');
    $this->addSql('ALTER TABLE Salgshistorik CHANGE adresse1 adresse1 VARCHAR(120) DEFAULT NULL');
    $this->addSql('ALTER TABLE Salgshistorik CHANGE land1 land1 VARCHAR(50) DEFAULT NULL');
    $this->addSql('ALTER TABLE Salgshistorik CHANGE medKoeberEmail medKoeberEmail VARCHAR(120) DEFAULT NULL');

    // longtext to VARCHAR conversion
    $this->addSql('ALTER TABLE Opkoeb CHANGE ejerlav ejerlav VARCHAR(60) DEFAULT NULL');

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
