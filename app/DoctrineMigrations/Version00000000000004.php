<?php

namespace Application\Migrations;

use AppBundle\Entity\User;
use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Migration:
 * - Migrate legacy fields to Symfony format
 */
class Version00000000000004 extends AbstractMigration
{
  /**
   * @param Schema $schema
   */
  public function up(Schema $schema)
  {
    // this up() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $entities = [
      // Table name, Last Coloumn Name
      ['Delomraade', 'mulighedFor'],
      ['Grund', 'notat'],
      ['Interessent', 'notat'],
      ['Landinspektoer', 'active'],
      ['Lokalplan', 'forbrugsAndel'],
      ['Lokalsamfund', 'active'],
      ['Opkoeb', 'procentAfLP'],
      ['PostBy', 'city'],
      ['Salgshistorik', 'notat'],
      ['Salgsomraade', 'lokalplanId']
    ];

    foreach ($entities as $entity) {
      $this->addSql('ALTER TABLE '.$entity[0].' CHANGE id id BIGINT AUTO_INCREMENT NOT NULL');
      $this->addSql('ALTER TABLE '.$entity[0].' CHANGE createdDate created_at DATETIME NOT NULL AFTER '.$entity[1]);
      $this->addSql('ALTER TABLE '.$entity[0].' CHANGE createdBy created_by VARCHAR(255) DEFAULT NULL AFTER created_at');
      $this->addSql('ALTER TABLE '.$entity[0].' CHANGE modifiedDate updated_at DATETIME NOT NULL AFTER created_by');
      $this->addSql('ALTER TABLE '.$entity[0].' CHANGE modifiedBy updated_by VARCHAR(255) DEFAULT NULL AFTER updated_at');
    }

    $this->addSql('ALTER TABLE Grund CHANGE postbyId postbyId BIGINT DEFAULT NULL');
    $this->addSql('ALTER TABLE Landinspektoer CHANGE postnrId postbyId BIGINT DEFAULT NULL');
    $this->addSql('ALTER TABLE Lokalplan CHANGE lsnr LokalsamfundId BIGINT DEFAULT NULL');

    // Drop required before rename allowed
    $this->addSql('ALTER TABLE Opkoeb DROP FOREIGN KEY fk_Opkoeb_lpId');
    $this->addSql('ALTER TABLE Opkoeb DROP INDEX fk_Opkoeb_lpId');
    $this->addSql('ALTER TABLE Opkoeb CHANGE lpId lokalplanId BIGINT DEFAULT NULL');

    $this->addSql('ALTER TABLE InteressentGrundMapping CHANGE id id BIGINT AUTO_INCREMENT NOT NULL');
    $this->addSql('ALTER TABLE Keyword CHANGE id id BIGINT AUTO_INCREMENT NOT NULL');
    $this->addSql('ALTER TABLE KeywordValue CHANGE id id BIGINT AUTO_INCREMENT NOT NULL, CHANGE keywordId keywordId BIGINT DEFAULT NULL');

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
