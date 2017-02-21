<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Migration:
 * - Flip boolean annonceresEj to annonceres
 * - Add annonceres to Salgsomraade
 */
class Version20170221100104 extends AbstractMigration
{
  /**
   * @param Schema $schema
   */
  public function up(Schema $schema)
  {
    // this up() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('ALTER TABLE Grund CHANGE annonceresej annonceres TINYINT(1) DEFAULT NULL');
    $this->addSql('UPDATE Grund SET annonceres = NOT annonceres');

    $this->addSql('ALTER TABLE Salgsomraade ADD annonceres TINYINT(1) DEFAULT \'0\' NOT NULL AFTER postById');
  }

  /**
   * @param Schema $schema
   */
  public function down(Schema $schema)
  {
    // this down() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('UPDATE Grund SET annonceres = NOT annonceres');
    $this->addSql('ALTER TABLE Grund CHANGE annonceres annonceresEj TINYINT(1) DEFAULT NULL');

    $this->addSql('ALTER TABLE Salgsomraade DROP annonceres');
  }
}
