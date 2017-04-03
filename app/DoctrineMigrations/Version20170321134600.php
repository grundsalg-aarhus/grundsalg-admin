<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170321134600 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE INDEX search_Delomraade_anvendelse ON Delomraade (anvendelse)');
        $this->addSql('CREATE INDEX search_Delomraade_mulighedFor ON Delomraade (mulighedFor)');
        $this->addSql('CREATE INDEX search_Grund_vej ON Grund (vej)');
        $this->addSql('CREATE INDEX search_Grund_husnummer ON Grund (husnummer)');
        $this->addSql('CREATE INDEX search_Grund_bogstav ON Grund (bogstav)');
        $this->addSql('CREATE INDEX search_Grund_salgsType ON Grund (salgsType)');
        $this->addSql('CREATE INDEX search_Grund_areal ON Grund (areal)');
        $this->addSql('CREATE INDEX search_Grund_pris ON Grund (pris)');
        $this->addSql('CREATE INDEX search_Interessent_koeberNavn ON Interessent (koeberNavn)');
        $this->addSql('CREATE INDEX search_Interessent_koeberEmail ON Interessent (koeberEmail)');
        $this->addSql('CREATE INDEX search_Interessent_koeberLand ON Interessent (koeberLand)');
        $this->addSql('CREATE INDEX search_Landinspektoer_active ON Landinspektoer (active)');
        $this->addSql('CREATE INDEX search_Landinspektoer_navn ON Landinspektoer (navn)');
        $this->addSql('CREATE INDEX search_Landinspektoer_email ON Landinspektoer (email)');
        $this->addSql('CREATE INDEX search_Landinspektoer_telefon ON Landinspektoer (telefon)');
        $this->addSql('CREATE INDEX search_Lokalplan_nr ON Lokalplan (nr)');
        $this->addSql('CREATE INDEX search_Lokalplan_titel ON Lokalplan (titel)');
        $this->addSql('CREATE INDEX search_Lokalplan_samletAreal ON Lokalplan (samletAreal)');
        $this->addSql('CREATE INDEX search_Lokalplan_salgbartAreal ON Lokalplan (salgbartAreal)');
        $this->addSql('CREATE INDEX search_Lokalplan_forbrugsAndel ON Lokalplan (forbrugsAndel)');
        $this->addSql('CREATE INDEX search_Lokalsamfund_active ON Lokalsamfund (active)');
        $this->addSql('CREATE INDEX search_Lokalsamfund_number ON Lokalsamfund (number)');
        $this->addSql('CREATE INDEX search_Lokalsamfund_name ON Lokalsamfund (name)');
        $this->addSql('CREATE INDEX search_Opkoeb_ejerlav ON Opkoeb (ejerlav)');
        $this->addSql('CREATE INDEX search_Opkoeb_m2 ON Opkoeb (m2)');
        $this->addSql('CREATE INDEX search_Opkoeb_opkoebDato ON Opkoeb (opkoebDato)');
        $this->addSql('CREATE INDEX search_Opkoeb_pris ON Opkoeb (pris)');
        $this->addSql('CREATE INDEX search_Opkoeb_procentAfLP ON Opkoeb (procentAfLP)');
        $this->addSql('CREATE INDEX search_PostBy_postalcode ON PostBy (postalCode)');
        $this->addSql('CREATE INDEX search_PostBy_city ON PostBy (city)');
        $this->addSql('CREATE INDEX search_Salgsomraade_titel ON Salgsomraade (titel)');
        $this->addSql('CREATE INDEX search_Salgsomraade_nr ON Salgsomraade (nr)');
        $this->addSql('CREATE INDEX search_Salgsomraade_type ON Salgsomraade (type)');
        $this->addSql('CREATE INDEX search_User_enabled ON fos_user (enabled)');
        $this->addSql('CREATE INDEX search_User_username ON fos_user (username)');
        $this->addSql('CREATE INDEX search_User_name ON fos_user (name)');
        $this->addSql('CREATE INDEX search_User_email ON fos_user (email)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX search_Delomraade_anvendelse ON Delomraade');
        $this->addSql('DROP INDEX search_Delomraade_mulighedFor ON Delomraade');
        $this->addSql('DROP INDEX search_Grund_vej ON Grund');
        $this->addSql('DROP INDEX search_Grund_husnummer ON Grund');
        $this->addSql('DROP INDEX search_Grund_bogstav ON Grund');
        $this->addSql('DROP INDEX search_Grund_salgsType ON Grund');
        $this->addSql('DROP INDEX search_Grund_areal ON Grund');
        $this->addSql('DROP INDEX search_Grund_pris ON Grund');
        $this->addSql('DROP INDEX search_Interessent_koeberNavn ON Interessent');
        $this->addSql('DROP INDEX search_Interessent_koeberEmail ON Interessent');
        $this->addSql('DROP INDEX search_Interessent_koeberLand ON Interessent');
        $this->addSql('DROP INDEX search_Landinspektoer_active ON Landinspektoer');
        $this->addSql('DROP INDEX search_Landinspektoer_navn ON Landinspektoer');
        $this->addSql('DROP INDEX search_Landinspektoer_email ON Landinspektoer');
        $this->addSql('DROP INDEX search_Landinspektoer_telefon ON Landinspektoer');
        $this->addSql('DROP INDEX search_Lokalplan_nr ON Lokalplan');
        $this->addSql('DROP INDEX search_Lokalplan_titel ON Lokalplan');
        $this->addSql('DROP INDEX search_Lokalplan_samletAreal ON Lokalplan');
        $this->addSql('DROP INDEX search_Lokalplan_salgbartAreal ON Lokalplan');
        $this->addSql('DROP INDEX search_Lokalplan_forbrugsAndel ON Lokalplan');
        $this->addSql('DROP INDEX search_Lokalsamfund_active ON Lokalsamfund');
        $this->addSql('DROP INDEX search_Lokalsamfund_number ON Lokalsamfund');
        $this->addSql('DROP INDEX search_Lokalsamfund_name ON Lokalsamfund');
        $this->addSql('DROP INDEX search_Opkoeb_ejerlav ON Opkoeb');
        $this->addSql('DROP INDEX search_Opkoeb_m2 ON Opkoeb');
        $this->addSql('DROP INDEX search_Opkoeb_opkoebDato ON Opkoeb');
        $this->addSql('DROP INDEX search_Opkoeb_pris ON Opkoeb');
        $this->addSql('DROP INDEX search_Opkoeb_procentAfLP ON Opkoeb');
        $this->addSql('DROP INDEX search_PostBy_postalcode ON PostBy');
        $this->addSql('DROP INDEX search_PostBy_city ON PostBy');
        $this->addSql('DROP INDEX search_Salgsomraade_titel ON Salgsomraade');
        $this->addSql('DROP INDEX search_Salgsomraade_nr ON Salgsomraade');
        $this->addSql('DROP INDEX search_Salgsomraade_type ON Salgsomraade');
        $this->addSql('DROP INDEX search_User_enabled ON fos_user');
        $this->addSql('DROP INDEX search_User_username ON fos_user');
        $this->addSql('DROP INDEX search_User_name ON fos_user');
        $this->addSql('DROP INDEX search_User_email ON fos_user');
    }
}
