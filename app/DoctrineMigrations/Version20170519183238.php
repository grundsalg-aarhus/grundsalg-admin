<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170519183238 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Grund CHANGE antalKorr1 antalKorr1 INT DEFAULT NULL, CHANGE antalKorr2 antalKorr2 INT DEFAULT NULL, CHANGE antalKorr3 antalKorr3 INT DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Grund CHANGE antalKorr1 antalKorr1 DOUBLE PRECISION DEFAULT NULL, CHANGE antalKorr2 antalKorr2 DOUBLE PRECISION DEFAULT NULL, CHANGE antalKorr3 antalKorr3 DOUBLE PRECISION DEFAULT NULL');
    }
}
