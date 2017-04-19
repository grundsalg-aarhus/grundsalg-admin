<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Converting 'tilslutning' field from legacy array format to doctrine array format.
 * E.g. from 'Kloak#Vand#El' to 'a:3:{i:0;s:5:"Kloak";i:1;s:4:"Vand";i:2;s:2:"El";}' or similar
 */
class Version20170403210711 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Grund ADD tilsluttet2 LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\' AFTER tilsluttet');
        $this->addSql("
            UPDATE `Grund` g LEFT JOIN (SELECT 
            id, count, tilsluttet, 
            CASE count
              WHEN NULL THEN 'a:0:{}'
            # 	a:1:{i:0;s:5:\"Kloak\";}
              WHEN 0 THEN CONCAT('a:1:{i:0;s:', CHAR_LENGTH(tilsluttet), ':\"', tilsluttet, '\";}')
            # 	a:2:{i:0;s:5:\"Kloak\";i:1;s:4:\"Vand\";}
              WHEN 1 THEN 
                CONCAT('a:2:{',
                'i:0;s:', CHAR_LENGTH(SUBSTR(tilsluttet, 1, LOCATE('#', tilsluttet)-1)), ':\"', SUBSTR(tilsluttet, 1, LOCATE('#', tilsluttet)-1), '\";'
                'i:1;s:', CHAR_LENGTH(SUBSTRING_INDEX(tilsluttet, '#', -1)), ':\"', SUBSTRING_INDEX(tilsluttet, '#', -1),
                '\";}')
            #	a:3:{i:0;s:5:\"Kloak\";i:1;s:4:\"Vand\";i:2;s:2:\"El\";}
              WHEN 2 THEN 
                CONCAT('a:3:{',
                'i:0;s:', CHAR_LENGTH(SUBSTR(tilsluttet, 1, LOCATE('#', tilsluttet)-1)), ':\"', SUBSTR(tilsluttet, 1, LOCATE('#', tilsluttet)-1), '\";'
                'i:1;s:', CHAR_LENGTH(SUBSTR(SUBSTRING_INDEX(tilsluttet, '#', -2), 1, LOCATE('#', SUBSTRING_INDEX(tilsluttet, '#', -2))-1)), ':\"', SUBSTR(SUBSTRING_INDEX(tilsluttet, '#', -2), 1, LOCATE('#', SUBSTRING_INDEX(tilsluttet, '#', -2))-1), '\";',
                'i:2;s:', CHAR_LENGTH(SUBSTRING_INDEX(tilsluttet, '#', -1)), ':\"', SUBSTRING_INDEX(tilsluttet, '#', -1),
                '\";}')
            #	a:4:{i:0;s:5:\"Kloak\";i:1;s:4:\"Vand\";i:2;s:2:\"El\";i:3;s:10:\"Fjernvarme\";}
              WHEN 3 THEN 
                CONCAT('a:4:{',
                'i:0;s:', CHAR_LENGTH(SUBSTR(tilsluttet, 1, LOCATE('#', tilsluttet)-1)), ':\"', SUBSTR(tilsluttet, 1, LOCATE('#', tilsluttet)-1), '\";'
                'i:1;s:', CHAR_LENGTH(SUBSTR(SUBSTRING_INDEX(tilsluttet, '#', -3), 1, LOCATE('#', SUBSTRING_INDEX(tilsluttet, '#', -3))-1)), ':\"', SUBSTR(SUBSTRING_INDEX(tilsluttet, '#', -3), 1, LOCATE('#', SUBSTRING_INDEX(tilsluttet, '#', -3))-1), '\";',
                'i:2;s:', CHAR_LENGTH(SUBSTR(SUBSTRING_INDEX(tilsluttet, '#', -2), 1, LOCATE('#', SUBSTRING_INDEX(tilsluttet, '#', -2))-1)), ':\"', SUBSTR(SUBSTRING_INDEX(tilsluttet, '#', -2), 1, LOCATE('#', SUBSTRING_INDEX(tilsluttet, '#', -2))-1), '\";',
                'i:3;s:', CHAR_LENGTH(SUBSTRING_INDEX(tilsluttet, '#', -1)), ':\"', SUBSTRING_INDEX(tilsluttet, '#', -1),
                '\";}')
                ELSE 'a:0:{}'
            END as value
            FROM
            (SELECT     
                id, tilsluttet, ROUND (   
                    (
                        CHAR_LENGTH(tilsluttet)
                        - CHAR_LENGTH( REPLACE ( tilsluttet, \"#\", \"\") ) 
                    ) / CHAR_LENGTH(\"#\")        
                ) AS count FROM `Grund`) as temp) as array_value
                ON array_value.id = g.id
            SET g.tilsluttet2 = array_value.value
        ");
      $this->addSql('ALTER TABLE Grund DROP tilsluttet');
      $this->addSql('ALTER TABLE Grund CHANGE tilsluttet2 tilsluttet LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\' AFTER datoAnnonce1');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Grund DROP tilsluttet');
        $this->addSql('ALTER TABLE Grund ADD tilsluttet VARCHAR(50) DEFAULT NULL');
    }
}
