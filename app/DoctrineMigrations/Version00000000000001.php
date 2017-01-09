<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Migration:
 * - Create schema matching legacy system
 */
class Version00000000000001 extends AbstractMigration
{
  /**
   * @param Schema $schema
   */
  public function up(Schema $schema)
  {
    // this up() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    // Add tables

    $this->addSql('
      CREATE TABLE Delomraade (
        id bigint(20) NOT NULL,
        lokalplanId bigint(20) DEFAULT NULL,
        kpl1 varchar(50) NOT NULL,
        kpl2 varchar(50) NOT NULL,
        kpl3 varchar(50) NOT NULL,
        kpl4 varchar(50) NOT NULL,
        o1 varchar(50) NOT NULL,
        o2 varchar(50) NOT NULL,
        o3 varchar(50) NOT NULL,
        anvendelse longtext NOT NULL,
        mulighedFor longtext NOT NULL,
        createdBy longtext NOT NULL,
        createdDate date NOT NULL,
        modifiedBy longtext NOT NULL,
        modifiedDate date NOT NULL,
        PRIMARY KEY (id),
        KEY fk_Delomraade_lokalplanId (lokalplanId)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8
    ');

    $this->addSql('
      CREATE TABLE Grund (
        id bigint(20) NOT NULL,
        createdBy longtext NOT NULL,
        createdDate date NOT NULL,
        modifiedBy longtext NOT NULL,
        modifiedDate date NOT NULL,
        status varchar(50) DEFAULT NULL,
        salgStatus varchar(50) DEFAULT NULL,
        salgsomraadeId bigint(20) DEFAULT NULL,
        gid varchar(50) DEFAULT NULL,
        grundType varchar(50) DEFAULT NULL,
        mnr longtext,
        mnr2 longtext,
        delAreal longtext,
        ejerlav longtext,
        vej longtext,
        husNummer longtext,
        bogstav longtext,
        postbyId longtext,
        lokalsamfundId bigint(20) DEFAULT NULL,
        urlGIS longtext,
        salgsType longtext,
        auktionStartDato date DEFAULT NULL,
        auktionSlutDato date DEFAULT NULL,
        annonceresEj varchar(50) DEFAULT NULL,
        datoAnnonce date DEFAULT NULL,
        datoAnnonce1 date DEFAULT NULL,
        tilsluttet longtext,
        maxEtageM2 float DEFAULT NULL,
        areal float DEFAULT NULL,
        arealVej float DEFAULT NULL,
        arealKotelet float DEFAULT NULL,
        bruttoAreal float DEFAULT NULL,
        prisM2 float DEFAULT NULL,
        prisFoerKorrektion float DEFAULT NULL,
        prisKorrektion1 longtext,
        antalKorr1 float DEFAULT NULL,
        aKrKorr1 float DEFAULT NULL,
        prisKoor1 float DEFAULT NULL,
        prisKorrektion2 longtext,
        antalKorr2 float DEFAULT NULL,
        aKrKorr2 float DEFAULT NULL,
        prisKoor2 float DEFAULT NULL,
        prisKorrektion3 longtext,
        antalKorr3 float DEFAULT NULL,
        aKrKorr3 float DEFAULT NULL,
        prisKoor3 float DEFAULT NULL,
        pris float DEFAULT NULL,
        fastPris float DEFAULT NULL,
        minBud float DEFAULT NULL,
        note longtext,
        landInspektoerId varchar(50) DEFAULT NULL,
        resStart date DEFAULT NULL,
        tilbudStart date DEFAULT NULL,
        accept date DEFAULT NULL,
        skoedeRekv date DEFAULT NULL,
        beloebAnvist date DEFAULT NULL,
        resSlut date DEFAULT NULL,
        tilbudSlut date DEFAULT NULL,
        overtagelse date DEFAULT NULL,
        antagetBud float DEFAULT NULL,
        salgsPrisUMoms float DEFAULT NULL,
        navn longtext,
        adresse longtext,
        koeberPostById bigint(20) DEFAULT NULL,
        land longtext,
        telefon varchar(50) DEFAULT NULL,
        mobil varchar(50) DEFAULT NULL,
        koeberEmail longtext,
        navn1 longtext,
        adresse1 longtext,
        medKoeberPostById bigint(20) DEFAULT NULL,
        land1 longtext,
        telefon1 varchar(50) DEFAULT NULL,
        mobil1 varchar(50) DEFAULT NULL,
        medKoeberEmail longtext,
        notat longtext,
        PRIMARY KEY (id),
        KEY fk_Grund_lokalsamfundId (lokalsamfundId),
        KEY fk_Grund_salgsomraadeId (salgsomraadeId),
        KEY fk_Grund_koeberPostById (koeberPostById),
        KEY fk_Grund_medKoeberPostById (medKoeberPostById)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8
    ');

    $this->addSql('
      CREATE TABLE Interessent (
        id bigint(20) NOT NULL,
        createdBy longtext NOT NULL,
        createdDate date NOT NULL,
        modifiedBy longtext NOT NULL,
        modifiedDate date NOT NULL,
        navn longtext,
        adresse longtext,
        koeberPostById bigint(20) DEFAULT NULL,
        land longtext,
        telefon varchar(50) DEFAULT NULL,
        mobil varchar(50) DEFAULT NULL,
        koeberEmail longtext,
        navn1 longtext,
        adresse1 longtext,
        medKoeberPostById bigint(20) DEFAULT NULL,
        land1 longtext,
        telefon1 varchar(50) DEFAULT NULL,
        mobil1 varchar(50) DEFAULT NULL,
        medKoeberEmail longtext,
        notat longtext,
        PRIMARY KEY (id),
        KEY fk_Interessent_koeberPostById (koeberPostById),
        KEY fk_Interessent_medKoeberPostById (medKoeberPostById)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8
    ');

    $this->addSql('
      CREATE TABLE InteressentGrundMapping (
        id bigint(20) NOT NULL,
        interessentId bigint(20) DEFAULT NULL,
        grundId bigint(20) DEFAULT NULL,
        annulleret varchar(50) NOT NULL,
        PRIMARY KEY (id),
        KEY fk_InteressentGrundMapping_interessentId (interessentId),
        KEY fk_InteressentGrundMapping_grundId (grundId)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8
    ');

    $this->addSql('
      CREATE TABLE Keyword (
        id bigint(20) NOT NULL,
        title varchar(50) NOT NULL,
        alias varchar(50) NOT NULL,
        description longtext NOT NULL,
        PRIMARY KEY (id)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8
    ');

    $this->addSql('
      CREATE TABLE KeywordValue (
        id bigint(20) NOT NULL,
        keywordId bigint(20) NOT NULL,
        display longtext NOT NULL,
        value longtext NOT NULL,
        PRIMARY KEY (id),
        KEY fk_KeywordValue_keywordId (keywordId)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8
    ');

    $this->addSql('
      CREATE TABLE Landinspektoer (
        id bigint(20) NOT NULL,
        adresse longtext NOT NULL,
        email longtext NOT NULL,
        mobil longtext NOT NULL,
        navn longtext NOT NULL,
        notat longtext NOT NULL,
        telefon longtext NOT NULL,
        postnrId bigint(20) NOT NULL,
        createdBy longtext NOT NULL,
        createdDate date NOT NULL,
        modifiedBy longtext NOT NULL,
        modifiedDate date NOT NULL,
        active int(11) NOT NULL,
        PRIMARY KEY (id)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8
    ');

    $this->addSql('
      CREATE TABLE Lokalplan (
        id bigint(20) NOT NULL,
        nr longtext NOT NULL,
        lsnr longtext NOT NULL,
        titel longtext NOT NULL,
        projektLeder longtext NOT NULL,
        telefon longtext NOT NULL,
        lokalPlanLink longtext NOT NULL,
        samletAreal longtext DEFAULT NULL,
        salgbartAreal longtext DEFAULT NULL,
        forbrugsAndel longtext NOT NULL,
        createdBy longtext NOT NULL,
        createdDate date NOT NULL,
        modifiedBy longtext NOT NULL,
        modifiedDate date NOT NULL,
        PRIMARY KEY (id)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8
    ');

    $this->addSql('
      CREATE TABLE Lokalsamfund (
        id bigint(20) NOT NULL,
        number varchar(50) NOT NULL,
        name longtext NOT NULL,
        createdBy longtext NOT NULL,
        createdDate date NOT NULL,
        modifiedBy longtext NOT NULL,
        modifiedDate date NOT NULL,
        active int(11) NOT NULL,
        PRIMARY KEY (id)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8
    ');

    $this->addSql('
      CREATE TABLE Opkoeb (
        id bigint(20) NOT NULL,
        lpId bigint(20) DEFAULT NULL,
        matrik1 varchar(50) DEFAULT NULL,
        matrik2 varchar(50) DEFAULT NULL,
        ejerlav longtext,
        m2 varchar(50) DEFAULT NULL,
        bemaerkning longtext,
        opkoebDato date DEFAULT NULL,
        pris varchar(50) DEFAULT NULL,
        procentAfLP varchar(50) DEFAULT NULL,
        createdBy longtext NOT NULL,
        createdDate date NOT NULL,
        modifiedBy longtext NOT NULL,
        modifiedDate date NOT NULL,
        PRIMARY KEY (id),
        KEY fk_Opkoeb_lpId (lpId)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8
    ');

    $this->addSql('
      CREATE TABLE PostBy (
        id bigint(20) NOT NULL,
        postalCode int(11) NOT NULL,
        city longtext NOT NULL,
        createdBy longtext NOT NULL,
        createdDate date NOT NULL,
        modifiedBy longtext NOT NULL,
        modifiedDate date NOT NULL,
        PRIMARY KEY (id)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8
    ');

    $this->addSql('
      CREATE TABLE Salgshistorik (
        id bigint(20) NOT NULL,
        grundId bigint(20) DEFAULT NULL,
        createdBy longtext NOT NULL,
        createdDate date NOT NULL,
        modifiedBy longtext NOT NULL,
        modifiedDate date NOT NULL,
        aarsag longtext,
        salgsType longtext,
        status longtext,
        resStart date DEFAULT NULL,
        resSlut date DEFAULT NULL,
        tilbudStart date DEFAULT NULL,
        tilbudSlut date DEFAULT NULL,
        accept date DEFAULT NULL,
        overtagelse date DEFAULT NULL,
        skoedeRekv date DEFAULT NULL,
        beloebAnvist date DEFAULT NULL,
        auktionStartDato date DEFAULT NULL,
        auktionSlutDato date DEFAULT NULL,
        minBud float DEFAULT NULL,
        antagetBud float DEFAULT NULL,
        navn longtext,
        adresse longtext,
        koeberPostById bigint(20) DEFAULT NULL,
        land longtext,
        telefon varchar(50) DEFAULT NULL,
        mobil varchar(50) DEFAULT NULL,
        koeberEmail longtext,
        navn1 longtext,
        adresse1 longtext,
        medKoeberPostById bigint(20) DEFAULT NULL,
        land1 longtext,
        telefon1 varchar(50) DEFAULT NULL,
        mobil1 varchar(50) DEFAULT NULL,
        medKoeberEmail longtext,
        notat longtext,
        PRIMARY KEY (id),
        KEY fk_Salgshistorik_grundId (grundId),
        KEY fk_Salgshistorik_koeberPostById (koeberPostById),
        KEY fk_Salgshistorik_medKoeberPostById (medKoeberPostById)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8
    ');

    $this->addSql('
      CREATE TABLE Salgsomraade (
        id bigint(20) NOT NULL,
        createdBy longtext NOT NULL,
        createdDate date NOT NULL,
        modifiedBy longtext NOT NULL,
        modifiedDate date NOT NULL,
        delomraadeId bigint(20) DEFAULT NULL,
        landinspektorId bigint(20) DEFAULT NULL,
        postById bigint(20) DEFAULT NULL,
        nr varchar(50) NOT NULL,
        titel longtext NOT NULL,
        type longtext NOT NULL,
        matrikkel1 longtext NOT NULL,
        matrikkel2 longtext NOT NULL,
        ejerlav longtext NOT NULL,
        vej longtext NOT NULL,
        gisUrl longtext NOT NULL,
        tilsluttet longtext NOT NULL,
        sagsNr longtext NOT NULL,
        lpLoebeNummer bigint(20) NOT NULL,
        lokalplanId bigint(20) DEFAULT NULL,
        PRIMARY KEY (id),
        KEY fk_Salgsomraade_postById (postById),
        KEY fk_Salgsomraade_delomraadeId (delomraadeId),
        KEY fk_Salgsomraade_lokalplanId (lokalplanId),
        KEY fk_Salgsomraade_landinspektorId (landinspektorId)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8
    ');

    $this->addSql('
      CREATE TABLE Users (
        id bigint(20) NOT NULL,
        name longtext NOT NULL,
        userName longtext NOT NULL,
        password longtext NOT NULL,
        roles longtext NOT NULL,
        createdBy longtext NOT NULL,
        createdDate date NOT NULL,
        modifiedBy longtext NOT NULL,
        modifiedDate date NOT NULL,
        PRIMARY KEY (id)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8
    ');


    // Add contraints


    $this->addSql('
      ALTER TABLE Delomraade ADD 
        CONSTRAINT fk_Delomraade_lokalplanId FOREIGN KEY (lokalplanId) REFERENCES Lokalplan (id)
    ');

    $this->addSql('
      ALTER TABLE Grund ADD
        CONSTRAINT fk_Grund_medKoeberPostById FOREIGN KEY (medKoeberPostById) REFERENCES PostBy (id);
      ALTER TABLE Grund ADD
        CONSTRAINT fk_Grund_koeberPostById FOREIGN KEY (koeberPostById) REFERENCES PostBy (id);
      ALTER TABLE Grund ADD
        CONSTRAINT fk_Grund_lokalsamfundId FOREIGN KEY (lokalsamfundId) REFERENCES Lokalsamfund (id);
      ALTER TABLE Grund ADD
        CONSTRAINT fk_Grund_salgsomraadeId FOREIGN KEY (salgsomraadeId) REFERENCES Salgsomraade (id);
    ');

    $this->addSql('
      ALTER TABLE Interessent ADD
        CONSTRAINT fk_Interessent_medKoeberPostById FOREIGN KEY (medKoeberPostById) REFERENCES PostBy (id);
      ALTER TABLE Interessent ADD
        CONSTRAINT fk_Interessent_koeberPostById FOREIGN KEY (koeberPostById) REFERENCES PostBy (id);
    ');

    $this->addSql('
      ALTER TABLE InteressentGrundMapping ADD
        CONSTRAINT fk_InteressentGrundMapping_grundId FOREIGN KEY (grundId) REFERENCES Grund (id);
      ALTER TABLE InteressentGrundMapping ADD
        CONSTRAINT fk_InteressentGrundMapping_interessentId FOREIGN KEY (interessentId) REFERENCES Interessent (id);
    ');

    $this->addSql('
      ALTER TABLE KeywordValue ADD
        CONSTRAINT fk_KeywordValue_keywordId FOREIGN KEY (keywordId) REFERENCES Keyword (id)
    ');

    $this->addSql('
      ALTER TABLE Opkoeb ADD
        CONSTRAINT fk_Opkoeb_lpId FOREIGN KEY (lpId) REFERENCES Lokalplan (id)
    ');

    $this->addSql('
      ALTER TABLE Salgshistorik ADD
        CONSTRAINT fk_Salgshistorik_medKoeberPostById FOREIGN KEY (medKoeberPostById) REFERENCES PostBy (id);
      ALTER TABLE Salgshistorik ADD
        CONSTRAINT fk_Salgshistorik_grundId FOREIGN KEY (grundId) REFERENCES Grund (id);
      ALTER TABLE Salgshistorik ADD
        CONSTRAINT fk_Salgshistorik_koeberPostById FOREIGN KEY (koeberPostById) REFERENCES PostBy (id)
    ');

    $this->addSql('
      ALTER TABLE Salgsomraade ADD
        CONSTRAINT fk_Salgsomraade_landinspektorId FOREIGN KEY (landinspektorId) REFERENCES Landinspektoer (id);
      ALTER TABLE Salgsomraade ADD
        CONSTRAINT fk_Salgsomraade_delomraadeId FOREIGN KEY (delomraadeId) REFERENCES Delomraade (id);
      ALTER TABLE Salgsomraade ADD
        CONSTRAINT fk_Salgsomraade_lokalplanId FOREIGN KEY (lokalplanId) REFERENCES Lokalplan (id);
      ALTER TABLE Salgsomraade ADD
        CONSTRAINT fk_Salgsomraade_postById FOREIGN KEY (postById) REFERENCES PostBy (id);
    ');

  }

  /**
   * @param Schema $schema
   */
  public function down(Schema $schema)
  {
    // this down() migration is auto-generated, please modify it to your needs
    $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    $this->addSql('
      ALTER TABLE Delomraade DROP 
        FOREIGN KEY fk_Delomraade_lokalplanId
    ');

    $this->addSql('
      ALTER TABLE Grund DROP
        FOREIGN KEY fk_Grund_medKoeberPostById;
      ALTER TABLE Grund DROP
        FOREIGN KEY fk_Grund_koeberPostById;
      ALTER TABLE Grund DROP
        FOREIGN KEY fk_Grund_lokalsamfundId;
      ALTER TABLE Grund DROP
        FOREIGN KEY fk_Grund_salgsomraadeId;
    ');

    $this->addSql('
      ALTER TABLE Interessent DROP
        FOREIGN KEY fk_Interessent_medKoeberPostById;
      ALTER TABLE Interessent DROP
        FOREIGN KEY fk_Interessent_koeberPostById;
    ');

    $this->addSql('
      ALTER TABLE InteressentGrundMapping DROP
        FOREIGN KEY fk_InteressentGrundMapping_grundId;
      ALTER TABLE InteressentGrundMapping DROP
        FOREIGN KEY fk_InteressentGrundMapping_interessentId;
    ');

    $this->addSql('
      ALTER TABLE KeywordValue DROP
        FOREIGN KEY fk_KeywordValue_keywordId
    ');

    $this->addSql('
      ALTER TABLE Opkoeb DROP
        FOREIGN KEY fk_Opkoeb_lpId
    ');

    $this->addSql('
      ALTER TABLE Salgshistorik DROP
        FOREIGN KEY fk_Salgshistorik_medKoeberPostById;
      ALTER TABLE Salgshistorik DROP
        FOREIGN KEY fk_Salgshistorik_grundId;
      ALTER TABLE Salgshistorik DROP
        FOREIGN KEY fk_Salgshistorik_koeberPostById
    ');

    $this->addSql('
      ALTER TABLE Salgsomraade DROP
        FOREIGN KEY fk_Salgsomraade_landinspektorId;
      ALTER TABLE Salgsomraade DROP
        FOREIGN KEY fk_Salgsomraade_delomraadeId;
      ALTER TABLE Salgsomraade DROP
        FOREIGN KEY fk_Salgsomraade_lokalplanId;
      ALTER TABLE Salgsomraade DROP
        FOREIGN KEY fk_Salgsomraade_postById;
    ');

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
    $this->addSql('DROP TABLE Users');

  }
}
