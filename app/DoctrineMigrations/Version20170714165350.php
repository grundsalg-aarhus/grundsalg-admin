<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170714165350 extends AbstractMigration {
  /**
   * @param Schema $schema
   */
  public function up(Schema $schema) {
    // this up() migration is auto-generated, please modify it to your needs
    $this->abortIf(
      $this->connection->getDatabasePlatform()
        ->getName() !== 'mysql',
      'Migration can only be executed safely on \'mysql\'.'
    );

    $this->addSql('ALTER TABLE Salgshistorik CHANGE navn koeberNavn VARCHAR(255) DEFAULT NULL');
    $this->addSql('ALTER TABLE Salgshistorik CHANGE adresse koeberAdresse VARCHAR(120) DEFAULT NULL');
    $this->addSql('ALTER TABLE Salgshistorik CHANGE land koeberLand VARCHAR(50) DEFAULT NULL');
    $this->addSql('ALTER TABLE Salgshistorik CHANGE telefon koeberTelefon VARCHAR(50) DEFAULT NULL');
    $this->addSql('ALTER TABLE Salgshistorik CHANGE mobil koeberMobil VARCHAR(50) DEFAULT NULL');

    $this->addSql('ALTER TABLE Salgshistorik CHANGE navn1 medkoeberNavn VARCHAR(255) DEFAULT NULL');
    $this->addSql('ALTER TABLE Salgshistorik CHANGE adresse1 medkoeberAdresse VARCHAR(120) DEFAULT NULL');
    $this->addSql('ALTER TABLE Salgshistorik CHANGE land1 medkoeberLand VARCHAR(50) DEFAULT NULL');
    $this->addSql('ALTER TABLE Salgshistorik CHANGE telefon1 medkoeberTelefon VARCHAR(50) DEFAULT NULL');
    $this->addSql('ALTER TABLE Salgshistorik CHANGE mobil1 medkoeberMobil VARCHAR(50) DEFAULT NULL');
    $this->addSql('ALTER TABLE Salgshistorik CHANGE medKoeberEmail medkoeberEmail VARCHAR(120) DEFAULT NULL');

    $this->addSql('ALTER TABLE Salgshistorik CHANGE notat koeberNotat LONGTEXT DEFAULT NULL');
  }

  /**
   * @param Schema $schema
   */
  public function down(Schema $schema) {
    // this down() migration is auto-generated, please modify it to your needs
    $this->abortIf(
      $this->connection->getDatabasePlatform()
        ->getName() !== 'mysql',
      'Migration can only be executed safely on \'mysql\'.'
    );

    $this->addSql('ALTER TABLE Salgshistorik CHANGE koeberNavn navn VARCHAR(255) DEFAULT NULL');
    $this->addSql('ALTER TABLE Salgshistorik CHANGE koeberAdresse adresse VARCHAR(100) DEFAULT NULL');
    $this->addSql('ALTER TABLE Salgshistorik CHANGE koeberLand land VARCHAR(50) DEFAULT NULL');
    $this->addSql('ALTER TABLE Salgshistorik CHANGE koeberTelefon telefon VARCHAR(50) DEFAULT NULL');
    $this->addSql('ALTER TABLE Salgshistorik CHANGE koeberMobil mobil VARCHAR(50) DEFAULT NULL');

    $this->addSql('ALTER TABLE Salgshistorik CHANGE medkoeberNavn navn1 VARCHAR(120) DEFAULT NULL');
    $this->addSql('ALTER TABLE Salgshistorik CHANGE medkoeberAdresse adresse1 VARCHAR(100) DEFAULT NULL');
    $this->addSql('ALTER TABLE Salgshistorik CHANGE medkoeberLand land1 VARCHAR(50) DEFAULT NULL');
    $this->addSql('ALTER TABLE Salgshistorik CHANGE medkoeberTelefon telefon1 VARCHAR(50) DEFAULT NULL');
    $this->addSql('ALTER TABLE Salgshistorik CHANGE mobil1 medkoeberMobil VARCHAR(50) DEFAULT NULL');
    $this->addSql('ALTER TABLE Salgshistorik CHANGE medkoeberEmail medKoeberEmail VARCHAR(120) DEFAULT NULL');

    $this->addSql('ALTER TABLE Salgshistorik CHANGE koeberNotat notat LONGTEXT DEFAULT NULL');
  }
}
