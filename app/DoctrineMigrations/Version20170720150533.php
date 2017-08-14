<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170720150533 extends AbstractMigration {
  /**
   * @param Schema $schema
   */
  public function up(Schema $schema) {
    // this up() migration is auto-generated, please modify it to your needs
    $this->abortIf(
      $this->connection->getDatabasePlatform()->getName() !== 'mysql',
      'Migration can only be executed safely on \'mysql\'.'
    );

    //Cleanup foreign keys missing
    $this->addSql('DELETE FROM Grund WHERE salgsomraadeId IS NULL');

    // Drop key/index required before rename allowed
    $this->addSql('ALTER TABLE Grund DROP FOREIGN KEY fk_Grund_lokalsamfundId');
    $this->addSql('ALTER TABLE Grund DROP INDEX fk_Grund_lokalsamfundId');

    $this->addSql('ALTER TABLE Grund DROP FOREIGN KEY fk_Grund_salgsomraadeId');
    $this->addSql('ALTER TABLE Grund DROP INDEX fk_Grund_salgsomraadeId');

    $this->addSql(
      'ALTER TABLE Grund 
            CHANGE salgsomraadeId salgsomraadeId INT NOT NULL, 
            CHANGE type type ENUM(\'Parcelhusgrund\', \'Erhvervsgrund\', \'Storparcel\', \'Andre\', \'Off. formål\', \'Centergrund\') NOT NULL COMMENT \'(DC2Type:GrundType)\', 
            CHANGE mnr mnr VARCHAR(20) NOT NULL, 
            CHANGE lokalsamfundId lokalsamfundId INT NOT NULL, 
            CHANGE salgsType salgsType ENUM(\'Auktion\', \'Etgm2\', \'Fastpris\', \'Kvadratmeterpris\') NOT NULL COMMENT \'(DC2Type:SalgsType)\''
    );

    $this->addSql('
      ALTER TABLE Grund ADD CONSTRAINT fk_Grund_lokalsamfundId FOREIGN KEY (lokalsamfundId) REFERENCES Lokalsamfund (id);
      ALTER TABLE Grund ADD CONSTRAINT fk_Grund_salgsomraadeId FOREIGN KEY (salgsomraadeId) REFERENCES Salgsomraade (id);
    ');
  }

  /**
   * @param Schema $schema
   */
  public function down(Schema $schema) {
    // this down() migration is auto-generated, please modify it to your needs
    $this->abortIf(
      $this->connection->getDatabasePlatform()->getName() !== 'mysql',
      'Migration can only be executed safely on \'mysql\'.'
    );

    $this->addSql(
      'ALTER TABLE Grund 
            CHANGE type type ENUM(\'Parcelhusgrund\', \'Erhvervsgrund\', \'Storparcel\', \'Andre\', \'Off. formål\', \'Centergrund\') DEFAULT NULL COLLATE utf8_general_ci COMMENT \'(DC2Type:GrundType)\', 
            CHANGE mnr mnr VARCHAR(20) DEFAULT NULL COLLATE utf8_general_ci, 
            CHANGE salgsType salgsType ENUM(\'Auktion\', \'Etgm2\', \'Fastpris\', \'Kvadratmeterpris\') DEFAULT NULL COLLATE utf8_general_ci COMMENT \'(DC2Type:SalgsType)\', 
            CHANGE lokalsamfundId lokalsamfundId INT DEFAULT NULL, 
            CHANGE salgsomraadeId salgsomraadeId INT DEFAULT NULL'
    );
  }
}
