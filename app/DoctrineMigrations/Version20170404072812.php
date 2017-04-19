<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170404072812 extends AbstractMigration {
  /**
   * @param Schema $schema
   */
  public function up(Schema $schema) {
    // this up() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('UPDATE Grund SET status = NULL WHERE status = " " ');
    $this->addSql('ALTER TABLE Grund CHANGE status status ENUM(\'Auktion slut\', \'Ledig\', \'Fremtidig\', \'Annonceret\', \'Solgt\', \'Reserveret\', \'I udbud\') DEFAULT NULL COMMENT \'(DC2Type:GrundStatus)\'');
  }

  /**
   * @param Schema $schema
   */
  public function down(Schema $schema) {
    // this down() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('ALTER TABLE Grund CHANGE status status VARCHAR(50) DEFAULT NULL COLLATE utf8_general_ci');
  }
}
