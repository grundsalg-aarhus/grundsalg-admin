<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160909111849 extends AbstractMigration
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
          $this->addSql('UPDATE ' . $entity .' SET created_by = createdBy');
          $this->addSql('UPDATE ' . $entity .' SET created_at = createdDate');
          $this->addSql('UPDATE ' . $entity .' SET updated_by = modifiedBy');
          $this->addSql('UPDATE ' . $entity .' SET updated_at = modifiedDate');
        }

        $this->addSql('ALTER TABLE Delomraade DROP createdBy, DROP createdDate, DROP modifiedBy, DROP modifiedDate');
        $this->addSql('ALTER TABLE Grund DROP createdBy, DROP createdDate, DROP modifiedBy, DROP modifiedDate');
        $this->addSql('ALTER TABLE Interessent DROP createdBy, DROP createdDate, DROP modifiedBy, DROP modifiedDate');
        $this->addSql('ALTER TABLE Landinspektoer DROP createdBy, DROP createdDate, DROP modifiedBy, DROP modifiedDate');
        $this->addSql('ALTER TABLE Lokalplan DROP createdBy, DROP createdDate, DROP modifiedBy, DROP modifiedDate');
        $this->addSql('ALTER TABLE Lokalsamfund DROP createdBy, DROP createdDate, DROP modifiedBy, DROP modifiedDate');
        $this->addSql('ALTER TABLE Opkoeb DROP createdBy, DROP createdDate, DROP modifiedBy, DROP modifiedDate');
        $this->addSql('ALTER TABLE PostBy DROP createdBy, DROP createdDate, DROP modifiedBy, DROP modifiedDate');
        $this->addSql('ALTER TABLE Salgshistorik DROP createdBy, DROP createdDate, DROP modifiedBy, DROP modifiedDate');
        $this->addSql('ALTER TABLE Salgsomraade DROP createdBy, DROP createdDate, DROP modifiedBy, DROP modifiedDate');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Delomraade ADD createdBy LONGTEXT NOT NULL COLLATE latin1_swedish_ci, ADD createdDate DATE NOT NULL, ADD modifiedBy LONGTEXT NOT NULL COLLATE latin1_swedish_ci, ADD modifiedDate DATE NOT NULL');
        $this->addSql('ALTER TABLE Grund ADD createdBy LONGTEXT NOT NULL COLLATE latin1_swedish_ci, ADD createdDate DATE NOT NULL, ADD modifiedBy LONGTEXT NOT NULL COLLATE latin1_swedish_ci, ADD modifiedDate DATE NOT NULL');
        $this->addSql('ALTER TABLE Interessent ADD createdBy LONGTEXT NOT NULL COLLATE latin1_swedish_ci, ADD createdDate DATE NOT NULL, ADD modifiedBy LONGTEXT NOT NULL COLLATE latin1_swedish_ci, ADD modifiedDate DATE NOT NULL');
        $this->addSql('ALTER TABLE Landinspektoer ADD createdBy LONGTEXT NOT NULL COLLATE latin1_swedish_ci, ADD createdDate DATE NOT NULL, ADD modifiedBy LONGTEXT NOT NULL COLLATE latin1_swedish_ci, ADD modifiedDate DATE NOT NULL');
        $this->addSql('ALTER TABLE Lokalplan ADD createdBy LONGTEXT NOT NULL COLLATE latin1_swedish_ci, ADD createdDate DATE NOT NULL, ADD modifiedBy LONGTEXT NOT NULL COLLATE latin1_swedish_ci, ADD modifiedDate DATE NOT NULL');
        $this->addSql('ALTER TABLE Lokalsamfund ADD createdBy LONGTEXT NOT NULL COLLATE latin1_swedish_ci, ADD createdDate DATE NOT NULL, ADD modifiedBy LONGTEXT NOT NULL COLLATE latin1_swedish_ci, ADD modifiedDate DATE NOT NULL');
        $this->addSql('ALTER TABLE Opkoeb ADD createdBy LONGTEXT NOT NULL COLLATE latin1_swedish_ci, ADD createdDate DATE NOT NULL, ADD modifiedBy LONGTEXT NOT NULL COLLATE latin1_swedish_ci, ADD modifiedDate DATE NOT NULL');
        $this->addSql('ALTER TABLE PostBy ADD createdBy LONGTEXT NOT NULL COLLATE latin1_swedish_ci, ADD createdDate DATE NOT NULL, ADD modifiedBy LONGTEXT NOT NULL COLLATE latin1_swedish_ci, ADD modifiedDate DATE NOT NULL');
        $this->addSql('ALTER TABLE Salgshistorik ADD createdBy LONGTEXT NOT NULL COLLATE latin1_swedish_ci, ADD createdDate DATE NOT NULL, ADD modifiedBy LONGTEXT NOT NULL COLLATE latin1_swedish_ci, ADD modifiedDate DATE NOT NULL');
        $this->addSql('ALTER TABLE Salgsomraade ADD createdBy LONGTEXT NOT NULL COLLATE latin1_swedish_ci, ADD createdDate DATE NOT NULL, ADD modifiedBy LONGTEXT NOT NULL COLLATE latin1_swedish_ci, ADD modifiedDate DATE NOT NULL');

      $entities = ['Delomraade', 'Grund', 'Interessent', 'Landinspektoer', 'Lokalplan', 'Lokalsamfund', 'Opkoeb', 'PostBy', 'Salgshistorik', 'Salgsomraade'];
      foreach ($entities as $entity) {
        $this->addSql('UPDATE ' . $entity .' SET createdBy = created_by');
        $this->addSql('UPDATE ' . $entity .' SET createdDate = created_at');
        $this->addSql('UPDATE ' . $entity .' SET modifiedBy = updated_by');
        $this->addSql('UPDATE ' . $entity .' SET modifiedDate = updated_at');
      }
    }
}
