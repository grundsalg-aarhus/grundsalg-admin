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

    $entities = ['Delomraade', 'Grund', 'Interessent', 'Landinspektoer', 'Lokalplan', 'Lokalsamfund', 'Opkoeb', 'PostBy', 'Salgshistorik', 'Salgsomraade'];

    foreach ($entities as $entity) {
      $this->addSql('ALTER TABLE '.$entity.' CHANGE id id BIGINT AUTO_INCREMENT NOT NULL');
      $this->addSql('ALTER TABLE '.$entity.' CHANGE createdDate created_at DATETIME NOT NULL');
      $this->addSql('ALTER TABLE '.$entity.' CHANGE createdBy created_by VARCHAR(255) DEFAULT NULL');
      $this->addSql('ALTER TABLE '.$entity.' CHANGE modifiedDate updated_at DATETIME NOT NULL');
      $this->addSql('ALTER TABLE '.$entity.' CHANGE modifiedBy updated_by VARCHAR(255) DEFAULT NULL');
    }

    $this->addSql('ALTER TABLE Grund CHANGE postbyId postbyId BIGINT DEFAULT NULL');
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

    $this->addSql('DROP TABLE fos_user');

  }

}
