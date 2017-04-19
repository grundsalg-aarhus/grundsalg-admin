<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170404124603 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Grund CHANGE salgStatus salgStatus ENUM(\'Accepteret\', \'Auktion igang\', \'Auktion slut\', \'Auktion annulleret\', \'Ledig\', \'Reservation annulleret\', \'Reserveret\', \'Skøde annulleret\', \'Skøde rekvireret\', \'Solgt\', \'Tilbud sendt\', \'Tilbud annulleret\') DEFAULT NULL COMMENT \'(DC2Type:GrundSalgStatus)\'');
        $this->addSql('ALTER TABLE Salgshistorik CHANGE salgsType salgsType ENUM(\'Auktion\', \'Etgm2\', \'Fastpris\', \'Kvadratmeterpris\') DEFAULT NULL COMMENT \'(DC2Type:SalgsType)\'');
        $this->addSql('ALTER TABLE Salgshistorik CHANGE status salgStatus ENUM(\'Accepteret\', \'Auktion igang\', \'Auktion slut\', \'Auktion annulleret\', \'Ledig\', \'Reservation annulleret\', \'Reserveret\', \'Skøde annulleret\', \'Skøde rekvireret\', \'Solgt\', \'Tilbud sendt\', \'Tilbud annulleret\') DEFAULT NULL COMMENT \'(DC2Type:GrundSalgStatus)\'');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Grund CHANGE salgStatus salgStatus VARCHAR(255) DEFAULT NULL COLLATE utf8_general_ci');
        $this->addSql('ALTER TABLE Salgshistorik CHANGE salgsType salgsType VARCHAR(30) DEFAULT NULL COLLATE utf8_general_ci, CHANGE status status VARCHAR(50) DEFAULT NULL COLLATE utf8_general_ci');
    }
}
