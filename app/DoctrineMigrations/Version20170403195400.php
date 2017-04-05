<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170403195400 extends AbstractMigration {
  /**
   * @param Schema $schema
   */
  public function up(Schema $schema) {
    // this up() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('ALTER TABLE Grund ADD type ENUM(\'Parcelhusgrund\', \'Erhvervsgrund\', \'Storparcel\', \'Andre\', \'Off. formål\', \'Centergrund\') DEFAULT NULL COMMENT \'(DC2Type:GrundType)\' AFTER GrundType');
    $this->addSql('UPDATE Grund SET type = grundType');
    $this->addSql('ALTER TABLE Grund DROP grundType');
  }

  /**
   * @param Schema $schema
   */
  public function down(Schema $schema) {
    // this down() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('ALTER TABLE Grund ADD grundType VARCHAR(50) DEFAULT NULL COLLATE utf8_general_ci');
    $this->addSql('UPDATE Grund SET grundType = type');
    $this->addSql('ALTER TABLE Grund DROP type');
  }
}
