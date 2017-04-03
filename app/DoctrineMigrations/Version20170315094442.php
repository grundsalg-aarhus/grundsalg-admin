<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170315094442 extends AbstractMigration {
  /**
   * @param Schema $schema
   */
  public function up(Schema $schema) {
    // this up() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('ALTER TABLE Interessent CHANGE navn koeberNavn VARCHAR(255) DEFAULT NULL');
    $this->addSql('ALTER TABLE Interessent CHANGE adresse koeberAdresse VARCHAR(120) DEFAULT NULL');
    $this->addSql('ALTER TABLE Interessent CHANGE land koeberLand VARCHAR(50) DEFAULT NULL');
    $this->addSql('ALTER TABLE Interessent CHANGE telefon koeberTelefon VARCHAR(50) DEFAULT NULL');
    $this->addSql('ALTER TABLE Interessent CHANGE mobil koeberMobil VARCHAR(50) DEFAULT NULL');

    $this->addSql('ALTER TABLE Interessent CHANGE navn1 medkoeberNavn VARCHAR(255) DEFAULT NULL');
    $this->addSql('ALTER TABLE Interessent CHANGE adresse1 medkoeberAdresse VARCHAR(120) DEFAULT NULL');
    $this->addSql('ALTER TABLE Interessent CHANGE land1 medkoeberLand VARCHAR(50) DEFAULT NULL');
    $this->addSql('ALTER TABLE Interessent CHANGE telefon1 medkoeberTelefon VARCHAR(50) DEFAULT NULL');
    $this->addSql('ALTER TABLE Interessent CHANGE mobil1 medkoeberMobil VARCHAR(50) DEFAULT NULL');
    $this->addSql('ALTER TABLE Interessent CHANGE medKoeberEmail medkoeberEmail VARCHAR(120) DEFAULT NULL');

    $this->addSql('ALTER TABLE Interessent CHANGE notat koeberNotat LONGTEXT DEFAULT NULL');
  }

  /**
   * @param Schema $schema
   */
  public function down(Schema $schema) {
    // this down() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()
        ->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('ALTER TABLE Interessent CHANGE koeberNavn navn VARCHAR(255) DEFAULT NULL');
    $this->addSql('ALTER TABLE Interessent CHANGE koeberAdresse adresse VARCHAR(100) DEFAULT NULL');
    $this->addSql('ALTER TABLE Interessent CHANGE koeberLand land VARCHAR(50) DEFAULT NULL');
    $this->addSql('ALTER TABLE Interessent CHANGE koeberTelefon telefon VARCHAR(50) DEFAULT NULL');
    $this->addSql('ALTER TABLE Interessent CHANGE koeberMobil mobil VARCHAR(50) DEFAULT NULL');

    $this->addSql('ALTER TABLE Interessent CHANGE medkoeberNavn navn1 VARCHAR(120) DEFAULT NULL');
    $this->addSql('ALTER TABLE Interessent CHANGE medkoeberAdresse adresse1 VARCHAR(100) DEFAULT NULL');
    $this->addSql('ALTER TABLE Interessent CHANGE medkoeberLand land1 VARCHAR(50) DEFAULT NULL');
    $this->addSql('ALTER TABLE Interessent CHANGE medkoeberTelefon telefon1 VARCHAR(50) DEFAULT NULL');
    $this->addSql('ALTER TABLE Interessent CHANGE mobil1 medkoeberMobil VARCHAR(50) DEFAULT NULL');
    $this->addSql('ALTER TABLE Interessent CHANGE medkoeberEmail medKoeberEmail VARCHAR(120) DEFAULT NULL');

    $this->addSql('ALTER TABLE Interessent CHANGE koeberNotat notat LONGTEXT DEFAULT NULL');
  }
}
