<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170404121033 extends AbstractMigration {
  /**
   * @param Schema $schema
   */
  public function up(Schema $schema) {
    // this up() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('UPDATE Delomraade SET kpl4 = "Center" WHERE kpl4 = "CEnter" ');
    $this->addSql('UPDATE Delomraade SET kpl4 = NULL WHERE kpl4 = "" ');
    $this->addSql('ALTER TABLE Delomraade CHANGE kpl4 kpl4 ENUM(\'Center\', \'BL\', \'BO\', \'ER\', \'JO\', \'OF\', \'RE\') DEFAULT NULL COMMENT \'(DC2Type:Kpl4)\'');
  }

  /**
   * @param Schema $schema
   */
  public function down(Schema $schema) {
    // this down() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('ALTER TABLE Delomraade CHANGE kpl4 kpl4 VARCHAR(50) NOT NULL COLLATE utf8_general_ci');
  }
}
