<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161125143402 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE Delomraade (id BIGINT AUTO_INCREMENT NOT NULL, kpl1 VARCHAR(50) NOT NULL, kpl2 VARCHAR(50) NOT NULL, kpl3 VARCHAR(50) NOT NULL, kpl4 VARCHAR(50) NOT NULL, o1 VARCHAR(50) NOT NULL, o2 VARCHAR(50) NOT NULL, o3 VARCHAR(50) NOT NULL, anvendelse LONGTEXT NOT NULL, mulighedFor LONGTEXT NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, lokalplanId BIGINT DEFAULT NULL, INDEX fk_Delomraade_lokalplanId (lokalplanId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Grund (id BIGINT AUTO_INCREMENT NOT NULL, status VARCHAR(50) DEFAULT NULL, salgStatus VARCHAR(50) DEFAULT NULL, gid VARCHAR(50) DEFAULT NULL, grundType VARCHAR(50) DEFAULT NULL, mnr LONGTEXT DEFAULT NULL, mnr2 LONGTEXT DEFAULT NULL, delAreal LONGTEXT DEFAULT NULL, ejerlav LONGTEXT DEFAULT NULL, vej LONGTEXT DEFAULT NULL, husNummer LONGTEXT DEFAULT NULL, bogstav LONGTEXT DEFAULT NULL, urlGIS LONGTEXT DEFAULT NULL, salgsType LONGTEXT DEFAULT NULL, auktionStartDato DATE DEFAULT NULL, auktionSlutDato DATE DEFAULT NULL, annonceresEj VARCHAR(50) DEFAULT NULL, datoAnnonce DATE DEFAULT NULL, datoAnnonce1 DATE DEFAULT NULL, tilsluttet LONGTEXT DEFAULT NULL, maxEtageM2 DOUBLE PRECISION DEFAULT NULL, areal DOUBLE PRECISION DEFAULT NULL, arealVej DOUBLE PRECISION DEFAULT NULL, arealKotelet DOUBLE PRECISION DEFAULT NULL, bruttoAreal DOUBLE PRECISION DEFAULT NULL, prisM2 DOUBLE PRECISION DEFAULT NULL, prisFoerKorrektion DOUBLE PRECISION DEFAULT NULL, prisKorrektion1 LONGTEXT DEFAULT NULL, antalKorr1 DOUBLE PRECISION DEFAULT NULL, aKrKorr1 DOUBLE PRECISION DEFAULT NULL, prisKoor1 DOUBLE PRECISION DEFAULT NULL, prisKorrektion2 LONGTEXT DEFAULT NULL, antalKorr2 DOUBLE PRECISION DEFAULT NULL, aKrKorr2 DOUBLE PRECISION DEFAULT NULL, prisKoor2 DOUBLE PRECISION DEFAULT NULL, prisKorrektion3 LONGTEXT DEFAULT NULL, antalKorr3 DOUBLE PRECISION DEFAULT NULL, aKrKorr3 DOUBLE PRECISION DEFAULT NULL, prisKoor3 DOUBLE PRECISION DEFAULT NULL, pris DOUBLE PRECISION DEFAULT NULL, fastPris DOUBLE PRECISION DEFAULT NULL, minBud DOUBLE PRECISION DEFAULT NULL, note LONGTEXT DEFAULT NULL, landInspektoerId VARCHAR(50) DEFAULT NULL, resStart DATE DEFAULT NULL, tilbudStart DATE DEFAULT NULL, accept DATE DEFAULT NULL, skoedeRekv DATE DEFAULT NULL, beloebAnvist DATE DEFAULT NULL, resSlut DATE DEFAULT NULL, tilbudSlut DATE DEFAULT NULL, overtagelse DATE DEFAULT NULL, antagetBud DOUBLE PRECISION DEFAULT NULL, salgsPrisUMoms DOUBLE PRECISION DEFAULT NULL, navn LONGTEXT DEFAULT NULL, adresse LONGTEXT DEFAULT NULL, land LONGTEXT DEFAULT NULL, telefon VARCHAR(50) DEFAULT NULL, mobil VARCHAR(50) DEFAULT NULL, koeberEmail LONGTEXT DEFAULT NULL, navn1 LONGTEXT DEFAULT NULL, adresse1 LONGTEXT DEFAULT NULL, land1 LONGTEXT DEFAULT NULL, telefon1 VARCHAR(50) DEFAULT NULL, mobil1 VARCHAR(50) DEFAULT NULL, medKoeberEmail LONGTEXT DEFAULT NULL, notat LONGTEXT DEFAULT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, postbyId BIGINT DEFAULT NULL, medKoeberPostById BIGINT DEFAULT NULL, koeberPostById BIGINT DEFAULT NULL, lokalsamfundId BIGINT DEFAULT NULL, salgsomraadeId BIGINT DEFAULT NULL, INDEX IDX_E5C5280940376CF (postbyId), INDEX fk_Grund_lokalsamfundId (lokalsamfundId), INDEX fk_Grund_salgsomraadeId (salgsomraadeId), INDEX fk_Grund_koeberPostById (koeberPostById), INDEX fk_Grund_medKoeberPostById (medKoeberPostById), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Interessent (id BIGINT AUTO_INCREMENT NOT NULL, navn LONGTEXT DEFAULT NULL, adresse LONGTEXT DEFAULT NULL, land LONGTEXT DEFAULT NULL, telefon VARCHAR(50) DEFAULT NULL, mobil VARCHAR(50) DEFAULT NULL, koeberEmail LONGTEXT DEFAULT NULL, navn1 LONGTEXT DEFAULT NULL, adresse1 LONGTEXT DEFAULT NULL, land1 LONGTEXT DEFAULT NULL, telefon1 VARCHAR(50) DEFAULT NULL, mobil1 VARCHAR(50) DEFAULT NULL, medKoeberEmail LONGTEXT DEFAULT NULL, notat LONGTEXT DEFAULT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, medKoeberPostById BIGINT DEFAULT NULL, koeberPostById BIGINT DEFAULT NULL, INDEX fk_Interessent_koeberPostById (koeberPostById), INDEX fk_Interessent_medKoeberPostById (medKoeberPostById), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE InteressentGrundMapping (id BIGINT AUTO_INCREMENT NOT NULL, annulleret VARCHAR(50) NOT NULL, grundId BIGINT DEFAULT NULL, interessentId BIGINT DEFAULT NULL, INDEX fk_InteressentGrundMapping_interessentId (interessentId), INDEX fk_InteressentGrundMapping_grundId (grundId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Keyword (id BIGINT AUTO_INCREMENT NOT NULL, title VARCHAR(50) NOT NULL, alias VARCHAR(50) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE KeywordValue (id BIGINT AUTO_INCREMENT NOT NULL, display LONGTEXT NOT NULL, value LONGTEXT NOT NULL, keywordId BIGINT DEFAULT NULL, INDEX fk_KeywordValue_keywordId (keywordId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Landinspektoer (id BIGINT AUTO_INCREMENT NOT NULL, adresse LONGTEXT NOT NULL, email LONGTEXT NOT NULL, mobil LONGTEXT NOT NULL, navn LONGTEXT NOT NULL, notat LONGTEXT NOT NULL, telefon LONGTEXT NOT NULL, postnrId BIGINT NOT NULL, active INT NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Lokalplan (id BIGINT AUTO_INCREMENT NOT NULL, nr LONGTEXT NOT NULL, lsnr LONGTEXT NOT NULL, titel LONGTEXT NOT NULL, projektLeder LONGTEXT NOT NULL, telefon LONGTEXT NOT NULL, lokalPlanLink LONGTEXT NOT NULL, samletAreal LONGTEXT NOT NULL, salgbartAreal LONGTEXT NOT NULL, forbrugsAndel LONGTEXT NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Lokalsamfund (id BIGINT AUTO_INCREMENT NOT NULL, number VARCHAR(50) NOT NULL, name LONGTEXT NOT NULL, active INT NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Opkoeb (id BIGINT AUTO_INCREMENT NOT NULL, matrik1 VARCHAR(50) DEFAULT NULL, matrik2 VARCHAR(50) DEFAULT NULL, ejerlav LONGTEXT DEFAULT NULL, m2 VARCHAR(50) DEFAULT NULL, bemaerkning LONGTEXT DEFAULT NULL, opkoebDato DATE DEFAULT NULL, pris VARCHAR(50) DEFAULT NULL, procentAfLP VARCHAR(50) DEFAULT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, lpId BIGINT DEFAULT NULL, INDEX fk_Opkoeb_lpId (lpId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE PostBy (id BIGINT AUTO_INCREMENT NOT NULL, postalCode INT NOT NULL, city LONGTEXT NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Salgshistorik (id BIGINT AUTO_INCREMENT NOT NULL, aarsag LONGTEXT DEFAULT NULL, salgsType LONGTEXT DEFAULT NULL, status LONGTEXT DEFAULT NULL, resStart DATE DEFAULT NULL, resSlut DATE DEFAULT NULL, tilbudStart DATE DEFAULT NULL, tilbudSlut DATE DEFAULT NULL, accept DATE DEFAULT NULL, overtagelse DATE DEFAULT NULL, skoedeRekv DATE DEFAULT NULL, beloebAnvist DATE DEFAULT NULL, auktionStartDato DATE DEFAULT NULL, auktionSlutDato DATE DEFAULT NULL, minBud DOUBLE PRECISION DEFAULT NULL, antagetBud DOUBLE PRECISION DEFAULT NULL, navn LONGTEXT DEFAULT NULL, adresse LONGTEXT DEFAULT NULL, land LONGTEXT DEFAULT NULL, telefon VARCHAR(50) DEFAULT NULL, mobil VARCHAR(50) DEFAULT NULL, koeberEmail LONGTEXT DEFAULT NULL, navn1 LONGTEXT DEFAULT NULL, adresse1 LONGTEXT DEFAULT NULL, land1 LONGTEXT DEFAULT NULL, telefon1 VARCHAR(50) DEFAULT NULL, mobil1 VARCHAR(50) DEFAULT NULL, medKoeberEmail LONGTEXT DEFAULT NULL, notat LONGTEXT DEFAULT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, medKoeberPostById BIGINT DEFAULT NULL, grundId BIGINT DEFAULT NULL, koeberPostById BIGINT DEFAULT NULL, INDEX fk_Salgshistorik_grundId (grundId), INDEX fk_Salgshistorik_koeberPostById (koeberPostById), INDEX fk_Salgshistorik_medKoeberPostById (medKoeberPostById), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Salgsomraade (id BIGINT AUTO_INCREMENT NOT NULL, nr VARCHAR(50) NOT NULL, titel LONGTEXT NOT NULL, type LONGTEXT NOT NULL, matrikkel1 LONGTEXT NOT NULL, matrikkel2 LONGTEXT NOT NULL, ejerlav LONGTEXT NOT NULL, vej LONGTEXT NOT NULL, gisUrl LONGTEXT NOT NULL, tilsluttet LONGTEXT NOT NULL, sagsNr LONGTEXT NOT NULL, lpLoebeNummer BIGINT NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, landinspektorId BIGINT DEFAULT NULL, delomraadeId BIGINT DEFAULT NULL, lokalplanId BIGINT DEFAULT NULL, postById BIGINT DEFAULT NULL, INDEX fk_Salgsomraade_postById (postById), INDEX fk_Salgsomraade_delomraadeId (delomraadeId), INDEX fk_Salgsomraade_lokalplanId (lokalplanId), INDEX fk_Salgsomraade_landinspektorId (landinspektorId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fos_user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, locked TINYINT(1) NOT NULL, expired TINYINT(1) NOT NULL, expires_at DATETIME DEFAULT NULL, confirmation_token VARCHAR(255) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', credentials_expired TINYINT(1) NOT NULL, credentials_expire_at DATETIME DEFAULT NULL, name LONGTEXT DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_957A647992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_957A6479A0D96FBF (email_canonical), UNIQUE INDEX UNIQ_957A6479C05FB297 (confirmation_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Delomraade ADD CONSTRAINT FK_592B27916293C3EF FOREIGN KEY (lokalplanId) REFERENCES Lokalplan (id)');
        $this->addSql('ALTER TABLE Grund ADD CONSTRAINT FK_E5C5280940376CF FOREIGN KEY (postbyId) REFERENCES PostBy (id)');
        $this->addSql('ALTER TABLE Grund ADD CONSTRAINT FK_E5C5280FD5BC28F FOREIGN KEY (medKoeberPostById) REFERENCES PostBy (id)');
        $this->addSql('ALTER TABLE Grund ADD CONSTRAINT FK_E5C5280C26AB620 FOREIGN KEY (koeberPostById) REFERENCES PostBy (id)');
        $this->addSql('ALTER TABLE Grund ADD CONSTRAINT FK_E5C528072834E81 FOREIGN KEY (lokalsamfundId) REFERENCES Lokalsamfund (id)');
        $this->addSql('ALTER TABLE Grund ADD CONSTRAINT FK_E5C5280981CF0A6 FOREIGN KEY (salgsomraadeId) REFERENCES Salgsomraade (id)');
        $this->addSql('ALTER TABLE Interessent ADD CONSTRAINT FK_3AD4AE02FD5BC28F FOREIGN KEY (medKoeberPostById) REFERENCES PostBy (id)');
        $this->addSql('ALTER TABLE Interessent ADD CONSTRAINT FK_3AD4AE02C26AB620 FOREIGN KEY (koeberPostById) REFERENCES PostBy (id)');
        $this->addSql('ALTER TABLE InteressentGrundMapping ADD CONSTRAINT FK_F0E2694F5BE6DCA1 FOREIGN KEY (grundId) REFERENCES Grund (id)');
        $this->addSql('ALTER TABLE InteressentGrundMapping ADD CONSTRAINT FK_F0E2694F9A9BDEBE FOREIGN KEY (interessentId) REFERENCES Interessent (id)');
        $this->addSql('ALTER TABLE KeywordValue ADD CONSTRAINT FK_7EF451D1310DB7F3 FOREIGN KEY (keywordId) REFERENCES Keyword (id)');
        $this->addSql('ALTER TABLE Opkoeb ADD CONSTRAINT FK_A2849A5C6F21AEFA FOREIGN KEY (lpId) REFERENCES Lokalplan (id)');
        $this->addSql('ALTER TABLE Salgshistorik ADD CONSTRAINT FK_6EB361B1FD5BC28F FOREIGN KEY (medKoeberPostById) REFERENCES PostBy (id)');
        $this->addSql('ALTER TABLE Salgshistorik ADD CONSTRAINT FK_6EB361B15BE6DCA1 FOREIGN KEY (grundId) REFERENCES Grund (id)');
        $this->addSql('ALTER TABLE Salgshistorik ADD CONSTRAINT FK_6EB361B1C26AB620 FOREIGN KEY (koeberPostById) REFERENCES PostBy (id)');
        $this->addSql('ALTER TABLE Salgsomraade ADD CONSTRAINT FK_CC2C00A977F9226A FOREIGN KEY (landinspektorId) REFERENCES Landinspektoer (id)');
        $this->addSql('ALTER TABLE Salgsomraade ADD CONSTRAINT FK_CC2C00A9FFDCCF11 FOREIGN KEY (delomraadeId) REFERENCES Delomraade (id)');
        $this->addSql('ALTER TABLE Salgsomraade ADD CONSTRAINT FK_CC2C00A96293C3EF FOREIGN KEY (lokalplanId) REFERENCES Lokalplan (id)');
        $this->addSql('ALTER TABLE Salgsomraade ADD CONSTRAINT FK_CC2C00A93431D9F1 FOREIGN KEY (postById) REFERENCES PostBy (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Salgsomraade DROP FOREIGN KEY FK_CC2C00A9FFDCCF11');
        $this->addSql('ALTER TABLE InteressentGrundMapping DROP FOREIGN KEY FK_F0E2694F5BE6DCA1');
        $this->addSql('ALTER TABLE Salgshistorik DROP FOREIGN KEY FK_6EB361B15BE6DCA1');
        $this->addSql('ALTER TABLE InteressentGrundMapping DROP FOREIGN KEY FK_F0E2694F9A9BDEBE');
        $this->addSql('ALTER TABLE KeywordValue DROP FOREIGN KEY FK_7EF451D1310DB7F3');
        $this->addSql('ALTER TABLE Salgsomraade DROP FOREIGN KEY FK_CC2C00A977F9226A');
        $this->addSql('ALTER TABLE Delomraade DROP FOREIGN KEY FK_592B27916293C3EF');
        $this->addSql('ALTER TABLE Opkoeb DROP FOREIGN KEY FK_A2849A5C6F21AEFA');
        $this->addSql('ALTER TABLE Salgsomraade DROP FOREIGN KEY FK_CC2C00A96293C3EF');
        $this->addSql('ALTER TABLE Grund DROP FOREIGN KEY FK_E5C528072834E81');
        $this->addSql('ALTER TABLE Grund DROP FOREIGN KEY FK_E5C5280940376CF');
        $this->addSql('ALTER TABLE Grund DROP FOREIGN KEY FK_E5C5280FD5BC28F');
        $this->addSql('ALTER TABLE Grund DROP FOREIGN KEY FK_E5C5280C26AB620');
        $this->addSql('ALTER TABLE Interessent DROP FOREIGN KEY FK_3AD4AE02FD5BC28F');
        $this->addSql('ALTER TABLE Interessent DROP FOREIGN KEY FK_3AD4AE02C26AB620');
        $this->addSql('ALTER TABLE Salgshistorik DROP FOREIGN KEY FK_6EB361B1FD5BC28F');
        $this->addSql('ALTER TABLE Salgshistorik DROP FOREIGN KEY FK_6EB361B1C26AB620');
        $this->addSql('ALTER TABLE Salgsomraade DROP FOREIGN KEY FK_CC2C00A93431D9F1');
        $this->addSql('ALTER TABLE Grund DROP FOREIGN KEY FK_E5C5280981CF0A6');
        $this->addSql('DROP TABLE Delomraade');
        $this->addSql('DROP TABLE Grund');
        $this->addSql('DROP TABLE Interessent');
        $this->addSql('DROP TABLE InteressentGrundMapping');
        $this->addSql('DROP TABLE Keyword');
        $this->addSql('DROP TABLE KeywordValue');
        $this->addSql('DROP TABLE Landinspektoer');
        $this->addSql('DROP TABLE Lokalplan');
        $this->addSql('DROP TABLE Lokalsamfund');
        $this->addSql('DROP TABLE Opkoeb');
        $this->addSql('DROP TABLE PostBy');
        $this->addSql('DROP TABLE Salgshistorik');
        $this->addSql('DROP TABLE Salgsomraade');
        $this->addSql('DROP TABLE fos_user');
    }
}
