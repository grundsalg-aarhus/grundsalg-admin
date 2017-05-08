<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170508125229 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Opkoeb CHANGE matrik1 matrik1 VARCHAR(50) NOT NULL, CHANGE matrik2 matrik2 VARCHAR(50) NOT NULL, CHANGE ejerlav ejerlav VARCHAR(60) NOT NULL, CHANGE m2 m2 INT NOT NULL, CHANGE opkoebDato opkoebDato DATE NOT NULL, CHANGE pris pris DOUBLE PRECISION NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Opkoeb CHANGE matrik1 matrik1 VARCHAR(50) DEFAULT NULL COLLATE utf8_general_ci, CHANGE matrik2 matrik2 VARCHAR(50) DEFAULT NULL COLLATE utf8_general_ci, CHANGE ejerlav ejerlav VARCHAR(60) DEFAULT NULL COLLATE utf8_general_ci, CHANGE m2 m2 INT DEFAULT NULL, CHANGE opkoebDato opkoebDato DATE DEFAULT NULL, CHANGE pris pris DOUBLE PRECISION DEFAULT NULL');
    }
}
