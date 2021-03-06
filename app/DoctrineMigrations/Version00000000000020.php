<?php

namespace Application\Migrations;

use AppBundle\Entity\User;
use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Migration:
 * - Add fields to allow import form web/geodata DB
 */
class Version00000000000020 extends AbstractMigration
{

  /**
   * @param Schema $schema
   */
  public function up(Schema $schema)
  {
    // this up() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('ALTER TABLE Grund ADD SP_GEOMETRY GEOMETRY NULL COMMENT \'(DC2Type:geometry)\'');
    $this->addSql('ALTER TABLE Grund ADD srid INT DEFAULT NULL');
    $this->addSql('ALTER TABLE Grund ADD pdflink VARCHAR(255) DEFAULT NULL');
    $this->addSql('ALTER TABLE Grund ADD MI_STYLE VARCHAR(255) DEFAULT NULL');

    $this->addSql('ALTER TABLE Salgsomraade ADD SP_GEOMETRY GEOMETRY DEFAULT NULL COMMENT \'(DC2Type:geometry)\'');
    $this->addSql('ALTER TABLE Salgsomraade ADD srid INT DEFAULT NULL');
    $this->addSql('ALTER TABLE Salgsomraade ADD MI_STYLE VARCHAR(255) DEFAULT NULL');
  }

  /**
   * @param Schema $schema
   */
  public function down(Schema $schema)
  {
    // this down() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('ALTER TABLE Grund DROP SP_GEOMETRY');
    $this->addSql('ALTER TABLE Grund DROP srid');
    $this->addSql('ALTER TABLE Grund DROP pdflink');
  }

}
