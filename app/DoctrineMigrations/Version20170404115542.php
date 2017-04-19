<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Converting 'mulighedFor' field from legacy array format to doctrine array format.
 * E.g. from 'Kloak#Vand#El' to 'a:3:{i:0;s:5:"Kloak";i:1;s:4:"Vand";i:2;s:2:"El";}' or similar
 */
class Version20170404115542 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
      // this up() migration is auto-generated, please modify it to your needs
      $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

      $this->addSql('ALTER TABLE Delomraade ADD mulighedFor2 LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\' AFTER mulighedFor');
      $this->addSql("
            UPDATE `Delomraade` d LEFT JOIN (SELECT 
            id, count, mulighedFor, 
            CASE count
              WHEN NULL THEN 'a:0:{}'
              WHEN 0 THEN CONCAT('a:1:{i:0;s:', CHAR_LENGTH(mulighedFor), ':\"', mulighedFor, '\";}')
              WHEN 1 THEN 
                CONCAT('a:2:{',
                'i:0;s:', CHAR_LENGTH(SUBSTR(mulighedFor, 1, LOCATE('#', mulighedFor)-1)), ':\"', SUBSTR(mulighedFor, 1, LOCATE('#', mulighedFor)-1), '\";'
                'i:1;s:', CHAR_LENGTH(SUBSTRING_INDEX(mulighedFor, '#', -1)), ':\"', SUBSTRING_INDEX(mulighedFor, '#', -1),
                '\";}')
              WHEN 2 THEN 
                CONCAT('a:3:{',
                'i:0;s:', CHAR_LENGTH(SUBSTR(mulighedFor, 1, LOCATE('#', mulighedFor)-1)), ':\"', SUBSTR(mulighedFor, 1, LOCATE('#', mulighedFor)-1), '\";'
                'i:1;s:', CHAR_LENGTH(SUBSTR(SUBSTRING_INDEX(mulighedFor, '#', -2), 1, LOCATE('#', SUBSTRING_INDEX(mulighedFor, '#', -2))-1)), ':\"', SUBSTR(SUBSTRING_INDEX(mulighedFor, '#', -2), 1, LOCATE('#', SUBSTRING_INDEX(mulighedFor, '#', -2))-1), '\";',
                'i:2;s:', CHAR_LENGTH(SUBSTRING_INDEX(mulighedFor, '#', -1)), ':\"', SUBSTRING_INDEX(mulighedFor, '#', -1),
                '\";}')
              WHEN 3 THEN 
                CONCAT('a:4:{',
                'i:0;s:', CHAR_LENGTH(SUBSTR(mulighedFor, 1, LOCATE('#', mulighedFor)-1)), ':\"', SUBSTR(mulighedFor, 1, LOCATE('#', mulighedFor)-1), '\";'
                'i:1;s:', CHAR_LENGTH(SUBSTR(SUBSTRING_INDEX(mulighedFor, '#', -3), 1, LOCATE('#', SUBSTRING_INDEX(mulighedFor, '#', -3))-1)), ':\"', SUBSTR(SUBSTRING_INDEX(mulighedFor, '#', -3), 1, LOCATE('#', SUBSTRING_INDEX(mulighedFor, '#', -3))-1), '\";',
                'i:2;s:', CHAR_LENGTH(SUBSTR(SUBSTRING_INDEX(mulighedFor, '#', -2), 1, LOCATE('#', SUBSTRING_INDEX(mulighedFor, '#', -2))-1)), ':\"', SUBSTR(SUBSTRING_INDEX(mulighedFor, '#', -2), 1, LOCATE('#', SUBSTRING_INDEX(mulighedFor, '#', -2))-1), '\";',
                'i:3;s:', CHAR_LENGTH(SUBSTRING_INDEX(mulighedFor, '#', -1)), ':\"', SUBSTRING_INDEX(mulighedFor, '#', -1),
                '\";}')
                ELSE 'a:0:{}'
            END as value
            FROM
            (SELECT     
                id, mulighedFor, ROUND (   
                    (
                        CHAR_LENGTH(mulighedFor)
                        - CHAR_LENGTH( REPLACE ( mulighedFor, \"#\", \"\") ) 
                    ) / CHAR_LENGTH(\"#\")        
                ) AS count FROM `Delomraade`) as temp) as array_value
                ON array_value.id = d.id
            SET d.mulighedFor2 = array_value.value
        ");
      $this->addSql('ALTER TABLE Delomraade DROP mulighedFor');
      $this->addSql('ALTER TABLE Delomraade CHANGE mulighedFor2 mulighedFor LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\' AFTER anvendelse');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Delomraade CHANGE mulighedFor mulighedFor VARCHAR(50) DEFAULT NULL COLLATE utf8_general_ci');
    }
}
