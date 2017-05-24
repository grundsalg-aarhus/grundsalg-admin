<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170519161516 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Grund CHANGE prisKorrektion1 prisKorrektion1 ENUM(\'Beliggenhed\', \'Ekstra fundering\', \'Støttemur\', \'Støttet byggeri\') DEFAULT NULL COMMENT \'(DC2Type:Priskorrektion)\', CHANGE prisKorrektion2 prisKorrektion2 ENUM(\'Beliggenhed\', \'Ekstra fundering\', \'Støttemur\', \'Støttet byggeri\') DEFAULT NULL COMMENT \'(DC2Type:Priskorrektion)\', CHANGE prisKorrektion3 prisKorrektion3 ENUM(\'Beliggenhed\', \'Ekstra fundering\', \'Støttemur\', \'Støttet byggeri\') DEFAULT NULL COMMENT \'(DC2Type:Priskorrektion)\'');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Grund CHANGE prisKorrektion1 prisKorrektion1 VARCHAR(30) DEFAULT NULL COLLATE utf8_general_ci, CHANGE prisKorrektion2 prisKorrektion2 VARCHAR(30) DEFAULT NULL COLLATE utf8_general_ci, CHANGE prisKorrektion3 prisKorrektion3 VARCHAR(30) DEFAULT NULL COLLATE utf8_general_ci');
    }
}
