<?php

declare(strict_types=1);

namespace App\Infrastructure\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200618084258 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mssql', 'Migration can only be executed safely on \'mssql\'.');

        $this->addSql('ALTER TABLE activity DROP COLUMN latitude');
        $this->addSql('ALTER TABLE activity DROP COLUMN longitude');
        $this->addSql('ALTER TABLE walk ADD route GEOGRAPHY');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:linestring)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'walk\', N\'COLUMN\', route');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mssql', 'Migration can only be executed safely on \'mssql\'.');

        $this->addSql('CREATE SCHEMA db_accessadmin');
        $this->addSql('CREATE SCHEMA db_backupoperator');
        $this->addSql('CREATE SCHEMA db_datareader');
        $this->addSql('CREATE SCHEMA db_datawriter');
        $this->addSql('CREATE SCHEMA db_ddladmin');
        $this->addSql('CREATE SCHEMA db_denydatareader');
        $this->addSql('CREATE SCHEMA db_denydatawriter');
        $this->addSql('CREATE SCHEMA db_owner');
        $this->addSql('CREATE SCHEMA db_securityadmin');
        $this->addSql('CREATE SCHEMA dbo');
        $this->addSql('ALTER TABLE activity ADD latitude NUMERIC(10, 8)');
        $this->addSql('ALTER TABLE activity ADD longitude NUMERIC(11, 8)');
        $this->addSql('ALTER TABLE walk DROP COLUMN route');
    }
}
