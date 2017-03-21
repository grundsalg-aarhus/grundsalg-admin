<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170315011547 extends AbstractMigration {
  /**
   * @param Schema $schema
   */
  public function up(Schema $schema) {
    // this up() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()
        ->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('ALTER TABLE Grund CHANGE navn koeberNavn VARCHAR(255) DEFAULT NULL');
    $this->addSql('ALTER TABLE Grund CHANGE adresse koeberAdresse VARCHAR(120) DEFAULT NULL');
    $this->addSql('ALTER TABLE Grund CHANGE land koeberLand VARCHAR(50) DEFAULT NULL');
    $this->addSql('ALTER TABLE Grund CHANGE telefon koeberTelefon VARCHAR(50) DEFAULT NULL');
    $this->addSql('ALTER TABLE Grund CHANGE mobil koeberMobil VARCHAR(50) DEFAULT NULL');

    $this->addSql('ALTER TABLE Grund CHANGE navn1 medkoeberNavn VARCHAR(255) DEFAULT NULL');
    $this->addSql('ALTER TABLE Grund CHANGE adresse1 medkoeberAdresse VARCHAR(120) DEFAULT NULL');
    $this->addSql('ALTER TABLE Grund CHANGE land1 medkoeberLand VARCHAR(50) DEFAULT NULL');
    $this->addSql('ALTER TABLE Grund CHANGE telefon1 medkoeberTelefon VARCHAR(50) DEFAULT NULL');
    $this->addSql('ALTER TABLE Grund CHANGE mobil1 medkoeberMobil VARCHAR(50) DEFAULT NULL');
    $this->addSql('ALTER TABLE Grund CHANGE medKoeberEmail medkoeberEmail VARCHAR(120) DEFAULT NULL');

    $this->addSql('ALTER TABLE Grund CHANGE notat koeberNotat LONGTEXT DEFAULT NULL');

  }

  /**
   * @param Schema $schema
   */
  public function down(Schema $schema) {
    // this down() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()
        ->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('ALTER TABLE Grund CHANGE koeberNavn navn VARCHAR(255) DEFAULT NULL');
    $this->addSql('ALTER TABLE Grund CHANGE koeberAdresse adresse VARCHAR(100) DEFAULT NULL');
    $this->addSql('ALTER TABLE Grund CHANGE koeberLand land VARCHAR(50) DEFAULT NULL');
    $this->addSql('ALTER TABLE Grund CHANGE koeberTelefon telefon VARCHAR(50) DEFAULT NULL');
    $this->addSql('ALTER TABLE Grund CHANGE koeberMobil mobil VARCHAR(50) DEFAULT NULL');

    $this->addSql('ALTER TABLE Grund CHANGE medkoeberNavn navn1 VARCHAR(120) DEFAULT NULL');
    $this->addSql('ALTER TABLE Grund CHANGE medkoeberAdresse adresse1 VARCHAR(100) DEFAULT NULL');
    $this->addSql('ALTER TABLE Grund CHANGE medkoeberLand land1 VARCHAR(50) DEFAULT NULL');
    $this->addSql('ALTER TABLE Grund CHANGE medkoeberTelefon telefon1 VARCHAR(50) DEFAULT NULL');
    $this->addSql('ALTER TABLE Grund CHANGE mobil1 medkoeberMobil VARCHAR(50) DEFAULT NULL');
    $this->addSql('ALTER TABLE Grund CHANGE medkoeberEmail medKoeberEmail VARCHAR(120) DEFAULT NULL');

    $this->addSql('ALTER TABLE Grund CHANGE koeberNotat notat LONGTEXT DEFAULT NULL');

  }
}
