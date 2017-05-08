<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170508102505 extends AbstractMigration {
  /**
   * @param Schema $schema
   */
  public function up(Schema $schema) {
    // this up() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('ALTER TABLE Lokalplan DROP FOREIGN KEY FK_3DA18C6772834E81');

    $this->addSql('ALTER TABLE Lokalplan CHANGE LokalsamfundId lokalsamfundId INT NOT NULL, CHANGE forbrugsAndel forbrugsAndel DOUBLE PRECISION DEFAULT NULL');
    $this->addSql('ALTER TABLE Salgsomraade CHANGE type type ENUM(\'Parcelhusgrund\', \'Erhvervsgrund\', \'Storparcel\', \'Andre\', \'Off. formål\', \'Centergrund\') NOT NULL COMMENT \'(DC2Type:GrundType)\' ');

    $this->addSql('ALTER TABLE Lokalplan ADD CONSTRAINT FK_3DA18C6772834E81 FOREIGN KEY (lokalsamfundId) REFERENCES Lokalsamfund (id)');
  }

  /**
   * @param Schema $schema
   */
  public function down(Schema $schema) {
    // this down() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('ALTER TABLE Lokalplan ADD FOREIGN KEY FK_3DA18C6772834E81');

    $this->addSql('ALTER TABLE Lokalplan CHANGE lokalsamfundId LokalsamfundId INT DEFAULT NULL');
    $this->addSql('ALTER TABLE Salgsomraade CHANGE type type ENUM(\'Parcelhusgrund\', \'Erhvervsgrund\', \'Storparcel\', \'Andre\', \'Off. formål\', \'Centergrund\') DEFAULT NULL COLLATE utf8_general_ci COMMENT \'(DC2Type:GrundType)\' ');

    $this->addSql('ALTER TABLE Lokalplan ADD CONSTRAINT FK_3DA18C6772834E81 FOREIGN KEY (lokalsamfundId) REFERENCES Lokalsamfund (id)');
  }
}
