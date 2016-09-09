<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Migration:
 * - Removes Users
 */
class Version20160909103923 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE Users');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE Users (id BIGINT AUTO_INCREMENT NOT NULL, name LONGTEXT NOT NULL COLLATE latin1_swedish_ci, userName LONGTEXT NOT NULL COLLATE latin1_swedish_ci, password LONGTEXT NOT NULL COLLATE latin1_swedish_ci, roles LONGTEXT NOT NULL COLLATE latin1_swedish_ci, createdBy LONGTEXT NOT NULL COLLATE latin1_swedish_ci, createdDate DATE NOT NULL, modifiedBy LONGTEXT NOT NULL COLLATE latin1_swedish_ci, modifiedDate DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
    }
}
