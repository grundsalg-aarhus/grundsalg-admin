<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170721093744 extends AbstractMigration {
  /**
   * @param Schema $schema
   */
  public function up(Schema $schema) {
    // this up() migration is auto-generated, please modify it to your needs

    $this->addSql(
      '
      CREATE TABLE IF NOT EXISTS mapinfo_mapcatalog (
        SPATIALTYPE float default NULL,
        TABLENAME char(32) default NULL,
        OWNERNAME char(32) default NULL,
        SPATIALCOLUMN char(32) default NULL,
        DB_X_LL float default NULL,
        DB_Y_LL float default NULL,
        DB_X_UR float default NULL,
        DB_Y_UR float default NULL,
        COORDINATESYSTEM char(254) default NULL,
        SYMBOL char(254) default NULL,
        XCOLUMNNAME char(32) default NULL,
        YCOLUMNNAME char(32) default NULL
      ) ENGINE=MyISAM DEFAULT CHARSET=latin1;
    '
    );
  }

  /**
   * @param Schema $schema
   */
  public function down(Schema $schema) {
    // this down() migration is auto-generated, please modify it to your needs

    $this->addSql('DROP TABLE mapinfo_mapcatalog');
  }
}
