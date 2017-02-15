<?php

namespace Application\Migrations;

use AppBundle\Entity\User;
use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Migration:
 * - Add indexies and foreign key constraints
 */
class Version00000000000070 extends AbstractMigration
{
  /**
   * @param Schema $schema
   */
  public function up(Schema $schema)
  {
    // this up() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    // Set null if id unknown
    $this->addSql('CREATE INDEX IDX_E5C5280940376CF ON Grund (postbyId)');
    $this->addSql('ALTER TABLE Grund ADD CONSTRAINT FK_E5C5280940376CF FOREIGN KEY (postbyId) REFERENCES PostBy (id)');

    $this->addSql('ALTER TABLE Grund CHANGE landInspektoerId landInspektoerId int(11) DEFAULT NULL');
    $this->addSql('ALTER TABLE Grund ADD CONSTRAINT FK_E5C5280E826DFE8 FOREIGN KEY (landInspektoerId) REFERENCES Landinspektoer (id)');
    $this->addSql('CREATE INDEX IDX_E5C5280E826DFE8 ON Grund (landInspektoerId)');

    $this->addSql('ALTER TABLE Landinspektoer ADD CONSTRAINT FK_88949929940376CF FOREIGN KEY (postbyId) REFERENCES PostBy (id)');
    $this->addSql('CREATE INDEX IDX_88949929940376CF ON Landinspektoer (postbyId)');

    $this->addSql('ALTER TABLE Lokalplan ADD CONSTRAINT FK_3DA18C6772834E81 FOREIGN KEY (lokalsamfundId) REFERENCES Lokalsamfund (id)');
    $this->addSql('CREATE INDEX IDX_3DA18C6772834E81 ON Lokalplan (lokalsamfundId)');

    $this->addSql('ALTER TABLE Opkoeb ADD CONSTRAINT FK_A2849A5C6293C3EF FOREIGN KEY (lokalplanId) REFERENCES Lokalplan (id)');
    $this->addSql('CREATE INDEX fk_Opkoeb_lokalplanId ON Opkoeb (lokalplanId)');

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
