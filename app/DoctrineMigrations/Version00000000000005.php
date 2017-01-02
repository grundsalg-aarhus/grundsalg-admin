<?php

namespace Application\Migrations;

use AppBundle\Entity\User;
use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Migration:
 * - Add indexies and foreign key constraints
 */
class Version00000000000005 extends AbstractMigration
{
  /**
   * @param Schema $schema
   */
  public function up(Schema $schema)
  {
    // this up() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    // Set null if id unknown
    $this->addSql('UPDATE Grund SET postbyId = NULL WHERE postbyId NOT IN (SELECT id FROM PostBy)');
    $this->addSql('CREATE INDEX IDX_E5C5280940376CF ON Grund (postbyId)');
    $this->addSql('ALTER TABLE Grund ADD CONSTRAINT FK_E5C5280940376CF FOREIGN KEY (postbyId) REFERENCES PostBy (id)');

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
