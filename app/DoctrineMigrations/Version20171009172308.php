<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171009172308 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Grund CHANGE maxEtageM2 maxEtageM2 NUMERIC(12, 2) DEFAULT NULL, CHANGE areal areal NUMERIC(12, 2) DEFAULT NULL, CHANGE arealVej arealVej NUMERIC(12, 2) DEFAULT NULL, CHANGE arealKotelet arealKotelet NUMERIC(12, 2) DEFAULT NULL, CHANGE bruttoAreal bruttoAreal NUMERIC(12, 2) DEFAULT NULL, CHANGE prisM2 prisM2 NUMERIC(12, 2) DEFAULT NULL, CHANGE prisFoerKorrektion prisFoerKorrektion NUMERIC(12, 2) DEFAULT NULL, CHANGE aKrKorr1 aKrKorr1 NUMERIC(12, 2) DEFAULT NULL, CHANGE prisKoor1 prisKoor1 NUMERIC(12, 2) DEFAULT NULL, CHANGE aKrKorr2 aKrKorr2 NUMERIC(12, 2) DEFAULT NULL, CHANGE prisKoor2 prisKoor2 NUMERIC(12, 2) DEFAULT NULL, CHANGE aKrKorr3 aKrKorr3 NUMERIC(12, 2) DEFAULT NULL, CHANGE prisKoor3 prisKoor3 NUMERIC(12, 2) DEFAULT NULL, CHANGE pris pris NUMERIC(12, 2) DEFAULT NULL, CHANGE fastPris fastPris NUMERIC(12, 2) DEFAULT NULL, CHANGE minBud minBud NUMERIC(12, 2) DEFAULT NULL, CHANGE antagetBud antagetBud NUMERIC(12, 2) DEFAULT NULL, CHANGE salgsPrisUMoms salgsPrisUMoms NUMERIC(12, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE Lokalplan CHANGE forbrugsAndel forbrugsAndel NUMERIC(18, 12) DEFAULT NULL');
        $this->addSql('ALTER TABLE Opkoeb CHANGE pris pris NUMERIC(16, 2) NOT NULL, CHANGE procentAfLP procentAfLP NUMERIC(12, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE Salgshistorik CHANGE minBud minBud NUMERIC(12, 2) DEFAULT NULL, CHANGE antagetBud antagetBud NUMERIC(12, 2) DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Grund CHANGE maxEtageM2 maxEtageM2 DOUBLE PRECISION DEFAULT NULL, CHANGE areal areal DOUBLE PRECISION DEFAULT NULL, CHANGE arealVej arealVej DOUBLE PRECISION DEFAULT NULL, CHANGE arealKotelet arealKotelet DOUBLE PRECISION DEFAULT NULL, CHANGE bruttoAreal bruttoAreal DOUBLE PRECISION DEFAULT NULL, CHANGE prisM2 prisM2 DOUBLE PRECISION DEFAULT NULL, CHANGE prisFoerKorrektion prisFoerKorrektion DOUBLE PRECISION DEFAULT NULL, CHANGE aKrKorr1 aKrKorr1 DOUBLE PRECISION DEFAULT NULL, CHANGE prisKoor1 prisKoor1 DOUBLE PRECISION DEFAULT NULL, CHANGE aKrKorr2 aKrKorr2 DOUBLE PRECISION DEFAULT NULL, CHANGE prisKoor2 prisKoor2 DOUBLE PRECISION DEFAULT NULL, CHANGE aKrKorr3 aKrKorr3 DOUBLE PRECISION DEFAULT NULL, CHANGE prisKoor3 prisKoor3 DOUBLE PRECISION DEFAULT NULL, CHANGE pris pris DOUBLE PRECISION DEFAULT NULL, CHANGE fastPris fastPris DOUBLE PRECISION DEFAULT NULL, CHANGE minBud minBud DOUBLE PRECISION DEFAULT NULL, CHANGE antagetBud antagetBud DOUBLE PRECISION DEFAULT NULL, CHANGE salgsPrisUMoms salgsPrisUMoms DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE Lokalplan CHANGE forbrugsAndel forbrugsAndel DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE Opkoeb CHANGE pris pris DOUBLE PRECISION NOT NULL, CHANGE procentAfLP procentAfLP DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE Salgshistorik CHANGE minBud minBud DOUBLE PRECISION DEFAULT NULL, CHANGE antagetBud antagetBud DOUBLE PRECISION DEFAULT NULL');
    }
}
