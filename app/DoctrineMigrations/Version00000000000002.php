<?php

namespace Application\Migrations;

use AppBundle\Entity\User;
use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Migration:
 * - Alter fields to allow null on columns with empty cells before import
 */
class Version00000000000002 extends AbstractMigration
{
  /**
   * @param Schema $schema
   */
  public function up(Schema $schema)
  {
    // this up() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('ALTER TABLE Lokalplan CHANGE lokalPlanLink lokalPlanLink VARCHAR(255) DEFAULT NULL');
    $this->addSql('ALTER TABLE Lokalplan CHANGE samletAreal samletAreal longtext  DEFAULT NULL');
    $this->addSql('ALTER TABLE Lokalplan CHANGE salgbartAreal salgbartAreal longtext DEFAULT NULL');
    
    $this->addSql('ALTER TABLE Delomraade CHANGE anvendelse anvendelse LONGTEXT NULL');
    $this->addSql('ALTER TABLE Delomraade CHANGE mulighedFor mulighedFor LONGTEXT NULL');

    $this->addSql('ALTER TABLE Landinspektoer CHANGE mobil mobil LONGTEXT NULL');
    $this->addSql('ALTER TABLE Landinspektoer CHANGE email email LONGTEXT NULL');

    $this->addSql('ALTER TABLE Salgsomraade CHANGE ejerlav ejerlav LONGTEXT NULL');
    $this->addSql('ALTER TABLE Salgsomraade CHANGE gisUrl gisUrl LONGTEXT NULL');
    $this->addSql('ALTER TABLE Salgsomraade CHANGE matrikkel1 matrikkel1 LONGTEXT NULL');
    $this->addSql('ALTER TABLE Salgsomraade CHANGE matrikkel2 matrikkel2 LONGTEXT NULL');
    $this->addSql('ALTER TABLE Salgsomraade CHANGE sagsNr sagsNr LONGTEXT NULL');
    $this->addSql('ALTER TABLE Salgsomraade CHANGE tilsluttet tilsluttet LONGTEXT NULL');
    $this->addSql('ALTER TABLE Salgsomraade CHANGE vej vej LONGTEXT NULL');

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
