<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200709150500 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activity (id INT IDENTITY NOT NULL, maproyalty INT, name NVARCHAR(190) NOT NULL, start_grid_ref NVARCHAR(45), point GEOGRAPHY, short_description VARCHAR(MAX), description VARCHAR(MAX), searchable_description VARCHAR(MAX), status INT NOT NULL, online_friendly BIT NOT NULL, slug NVARCHAR(190), created_date DATETIME2(6) NOT NULL, modified_date DATETIME2(6) NOT NULL, createdUser INT NOT NULL, modifiedUser INT NOT NULL, type NVARCHAR(45) NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AC74095A5E237E06 ON activity (name) WHERE name IS NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AC74095A989D9B62 ON activity (slug) WHERE slug IS NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AC74095AF691CABA ON activity (maproyalty) WHERE maproyalty IS NOT NULL');
        $this->addSql('CREATE INDEX IDX_AC74095A7AF16D89 ON activity (createdUser)');
        $this->addSql('CREATE INDEX IDX_AC74095A160C1387 ON activity (modifiedUser)');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:point)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'activity\', N\'COLUMN\', point');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT DF_AC74095A_7B00651C DEFAULT 0 FOR status');
        $this->addSql('CREATE TABLE activity_tag (activity_id INT NOT NULL, tag_id INT NOT NULL, PRIMARY KEY (activity_id, tag_id))');
        $this->addSql('CREATE INDEX IDX_71B0290181C06096 ON activity_tag (activity_id)');
        $this->addSql('CREATE INDEX IDX_71B02901BAD26311 ON activity_tag (tag_id)');
        $this->addSql('CREATE TABLE associated_activity (activity_a_id INT NOT NULL, associated_b_id INT NOT NULL, PRIMARY KEY (activity_a_id, associated_b_id))');
        $this->addSql('CREATE INDEX IDX_DDDEC52C381A88CA ON associated_activity (activity_a_id)');
        $this->addSql('CREATE INDEX IDX_DDDEC52C98A168F1 ON associated_activity (associated_b_id)');
        $this->addSql('CREATE TABLE admin_notes (id INT IDENTITY NOT NULL, collection INT, activity INT, note NVARCHAR(255) NOT NULL, created_date DATETIME2(6) NOT NULL, modified_date DATETIME2(6) NOT NULL, createdUser INT NOT NULL, modifiedUser INT NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX IDX_A1315D76FC4D6532 ON admin_notes (collection)');
        $this->addSql('CREATE INDEX IDX_A1315D76AC74095A ON admin_notes (activity)');
        $this->addSql('CREATE INDEX IDX_A1315D767AF16D89 ON admin_notes (createdUser)');
        $this->addSql('CREATE INDEX IDX_A1315D76160C1387 ON admin_notes (modifiedUser)');
        $this->addSql('CREATE TABLE collection (id INT IDENTITY NOT NULL, name NVARCHAR(190) NOT NULL, description VARCHAR(MAX), status NVARCHAR(45) NOT NULL, slug NVARCHAR(255) NOT NULL, created_date DATETIME2(6) NOT NULL, modified_date DATETIME2(6) NOT NULL, createdUser INT NOT NULL, modifiedUser INT NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FC4D65325E237E06 ON collection (name) WHERE name IS NOT NULL');
        $this->addSql('CREATE INDEX IDX_FC4D65327AF16D89 ON collection (createdUser)');
        $this->addSql('CREATE INDEX IDX_FC4D6532160C1387 ON collection (modifiedUser)');
        $this->addSql('ALTER TABLE collection ADD CONSTRAINT DF_FC4D6532_7B00651C DEFAULT \'public\' FOR status');
        $this->addSql('CREATE TABLE collection_tag (collection_id INT NOT NULL, tag_id INT NOT NULL, PRIMARY KEY (collection_id, tag_id))');
        $this->addSql('CREATE INDEX IDX_AB0018E7514956FD ON collection_tag (collection_id)');
        $this->addSql('CREATE INDEX IDX_AB0018E7BAD26311 ON collection_tag (tag_id)');
        $this->addSql('CREATE TABLE collection_contents (id INT IDENTITY NOT NULL, collection INT NOT NULL, activity INT NOT NULL, position INT NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX IDX_457ADF47FC4D6532 ON collection_contents (collection)');
        $this->addSql('CREATE INDEX IDX_457ADF47AC74095A ON collection_contents (activity)');
        $this->addSql('CREATE TABLE direction (id INT IDENTITY NOT NULL, activity INT NOT NULL, position INT NOT NULL, direction VARCHAR(MAX) NOT NULL, created_date DATETIME2(6) NOT NULL, modified_date DATETIME2(6) NOT NULL, createdUser INT NOT NULL, modifiedUser INT NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX IDX_3E4AD1B3AC74095A ON direction (activity)');
        $this->addSql('CREATE INDEX IDX_3E4AD1B37AF16D89 ON direction (createdUser)');
        $this->addSql('CREATE INDEX IDX_3E4AD1B3160C1387 ON direction (modifiedUser)');
        $this->addSql('CREATE TABLE drive (id INT NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE TABLE flag_type (id INT IDENTITY NOT NULL, name NVARCHAR(255) NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE TABLE image (id INT IDENTITY NOT NULL, activity INT NOT NULL, name NVARCHAR(190) NOT NULL, type NVARCHAR(45) NOT NULL, created_date DATETIME2(6) NOT NULL, modified_date DATETIME2(6) NOT NULL, createdUser INT NOT NULL, modifiedUser INT NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX IDX_C53D045FAC74095A ON image (activity)');
        $this->addSql('CREATE INDEX IDX_C53D045F7AF16D89 ON image (createdUser)');
        $this->addSql('CREATE INDEX IDX_C53D045F160C1387 ON image (modifiedUser)');
        $this->addSql('CREATE TABLE map_royalty (id INT IDENTITY NOT NULL, activity INT NOT NULL, width DOUBLE PRECISION, height DOUBLE PRECISION, map_scale INT, source_scale INT, sea_area INT, created_date DATETIME2(6) NOT NULL, modified_date DATETIME2(6) NOT NULL, createdUser INT NOT NULL, modifiedUser INT NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX IDX_640D0E59AC74095A ON map_royalty (activity)');
        $this->addSql('CREATE INDEX IDX_640D0E597AF16D89 ON map_royalty (createdUser)');
        $this->addSql('CREATE INDEX IDX_640D0E59160C1387 ON map_royalty (modifiedUser)');
        $this->addSql('CREATE TABLE poi (id INT NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE TABLE ride (id INT NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE TABLE route_point (id INT IDENTITY NOT NULL, activity INT NOT NULL, direction INT, route_order INT NOT NULL, latitude NUMERIC(10, 8) NOT NULL, longitude NUMERIC(11, 8) NOT NULL, created_date DATETIME2(6) NOT NULL, modified_date DATETIME2(6) NOT NULL, createdUser INT NOT NULL, modifiedUser INT NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX IDX_2ADAC18AAC74095A ON route_point (activity)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2ADAC18A3E4AD1B3 ON route_point (direction) WHERE direction IS NOT NULL');
        $this->addSql('CREATE INDEX IDX_2ADAC18A7AF16D89 ON route_point (createdUser)');
        $this->addSql('CREATE INDEX IDX_2ADAC18A160C1387 ON route_point (modifiedUser)');
        $this->addSql('CREATE TABLE tag (id INT IDENTITY NOT NULL, name NVARCHAR(190) NOT NULL, created_date DATETIME2(6) NOT NULL, modified_date DATETIME2(6) NOT NULL, createdUser INT NOT NULL, modifiedUser INT NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_389B7835E237E06 ON tag (name) WHERE name IS NOT NULL');
        $this->addSql('CREATE INDEX IDX_389B7837AF16D89 ON tag (createdUser)');
        $this->addSql('CREATE INDEX IDX_389B783160C1387 ON tag (modifiedUser)');
        $this->addSql('CREATE TABLE tag_parent (tag_a_id INT NOT NULL, tag_b_id INT NOT NULL, PRIMARY KEY (tag_a_id, tag_b_id))');
        $this->addSql('CREATE INDEX IDX_D4CF2963D5D1ECEE ON tag_parent (tag_a_id)');
        $this->addSql('CREATE INDEX IDX_D4CF2963C7644300 ON tag_parent (tag_b_id)');
        $this->addSql('CREATE TABLE user_accounts (id INT IDENTITY NOT NULL, username NVARCHAR(180) NOT NULL, roles VARCHAR(MAX) NOT NULL, first_name NVARCHAR(255) NOT NULL, surname NVARCHAR(255) NOT NULL, email NVARCHAR(255) NOT NULL, password NVARCHAR(255) NOT NULL, created_at DATETIME2(6) NOT NULL, updated_at DATETIME2(6) NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2A457AACF85E0677 ON user_accounts (username) WHERE username IS NOT NULL');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:json)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'user_accounts\', N\'COLUMN\', roles');
        $this->addSql('CREATE TABLE user_flag (id INT IDENTITY NOT NULL, activity INT NOT NULL, description VARCHAR(MAX), created_date DATETIME2(6) NOT NULL, modified_date DATETIME2(6) NOT NULL, flagType INT NOT NULL, createdUser INT NOT NULL, modifiedUser INT NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX IDX_AB75A753AC74095A ON user_flag (activity)');
        $this->addSql('CREATE INDEX IDX_AB75A753DAA2006D ON user_flag (flagType)');
        $this->addSql('CREATE INDEX IDX_AB75A7537AF16D89 ON user_flag (createdUser)');
        $this->addSql('CREATE INDEX IDX_AB75A753160C1387 ON user_flag (modifiedUser)');
        $this->addSql('CREATE TABLE walk (id INT NOT NULL, location VARCHAR(MAX), distance DOUBLE PRECISION, minimum_time_hours INT, minimum_time_minutes INT, ascent INT, gradient INT, difficulty INT, paths NVARCHAR(500), landscape NVARCHAR(500), finish_grid_ref NVARCHAR(45), dog_friendliness VARCHAR(MAX), parking VARCHAR(MAX), public_toilet VARCHAR(MAX), notes VARCHAR(MAX), what_to_look_out_for VARCHAR(MAX), where_to_eat_and_drink VARCHAR(MAX), while_you_are_there VARCHAR(MAX), extension VARCHAR(MAX), suggested_map NVARCHAR(500), route GEOGRAPHY, PRIMARY KEY (id))');
        $this->addSql('EXEC sp_addextendedproperty N\'MS_Description\', N\'(DC2Type:linestring)\', N\'SCHEMA\', \'dbo\', N\'TABLE\', \'walk\', N\'COLUMN\', route');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095AF691CABA FOREIGN KEY (maproyalty) REFERENCES map_royalty (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095A7AF16D89 FOREIGN KEY (createdUser) REFERENCES user_accounts (id)');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095A160C1387 FOREIGN KEY (modifiedUser) REFERENCES user_accounts (id)');
        $this->addSql('ALTER TABLE activity_tag ADD CONSTRAINT FK_71B0290181C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activity_tag ADD CONSTRAINT FK_71B02901BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE associated_activity ADD CONSTRAINT FK_DDDEC52C381A88CA FOREIGN KEY (activity_a_id) REFERENCES activity (id)');
        $this->addSql('ALTER TABLE associated_activity ADD CONSTRAINT FK_DDDEC52C98A168F1 FOREIGN KEY (associated_b_id) REFERENCES activity (id)');
        $this->addSql('ALTER TABLE admin_notes ADD CONSTRAINT FK_A1315D76FC4D6532 FOREIGN KEY (collection) REFERENCES collection (id)');
        $this->addSql('ALTER TABLE admin_notes ADD CONSTRAINT FK_A1315D76AC74095A FOREIGN KEY (activity) REFERENCES activity (id)');
        $this->addSql('ALTER TABLE admin_notes ADD CONSTRAINT FK_A1315D767AF16D89 FOREIGN KEY (createdUser) REFERENCES user_accounts (id)');
        $this->addSql('ALTER TABLE admin_notes ADD CONSTRAINT FK_A1315D76160C1387 FOREIGN KEY (modifiedUser) REFERENCES user_accounts (id)');
        $this->addSql('ALTER TABLE collection ADD CONSTRAINT FK_FC4D65327AF16D89 FOREIGN KEY (createdUser) REFERENCES user_accounts (id)');
        $this->addSql('ALTER TABLE collection ADD CONSTRAINT FK_FC4D6532160C1387 FOREIGN KEY (modifiedUser) REFERENCES user_accounts (id)');
        $this->addSql('ALTER TABLE collection_tag ADD CONSTRAINT FK_AB0018E7514956FD FOREIGN KEY (collection_id) REFERENCES collection (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE collection_tag ADD CONSTRAINT FK_AB0018E7BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE collection_contents ADD CONSTRAINT FK_457ADF47FC4D6532 FOREIGN KEY (collection) REFERENCES collection (id)');
        $this->addSql('ALTER TABLE collection_contents ADD CONSTRAINT FK_457ADF47AC74095A FOREIGN KEY (activity) REFERENCES activity (id)');
        $this->addSql('ALTER TABLE direction ADD CONSTRAINT FK_3E4AD1B3AC74095A FOREIGN KEY (activity) REFERENCES activity (id)');
        $this->addSql('ALTER TABLE direction ADD CONSTRAINT FK_3E4AD1B37AF16D89 FOREIGN KEY (createdUser) REFERENCES user_accounts (id)');
        $this->addSql('ALTER TABLE direction ADD CONSTRAINT FK_3E4AD1B3160C1387 FOREIGN KEY (modifiedUser) REFERENCES user_accounts (id)');
        $this->addSql('ALTER TABLE drive ADD CONSTRAINT FK_681DF58FBF396750 FOREIGN KEY (id) REFERENCES activity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FAC74095A FOREIGN KEY (activity) REFERENCES activity (id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F7AF16D89 FOREIGN KEY (createdUser) REFERENCES user_accounts (id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F160C1387 FOREIGN KEY (modifiedUser) REFERENCES user_accounts (id)');
        $this->addSql('ALTER TABLE map_royalty ADD CONSTRAINT FK_640D0E59AC74095A FOREIGN KEY (activity) REFERENCES activity (id)');
        $this->addSql('ALTER TABLE map_royalty ADD CONSTRAINT FK_640D0E597AF16D89 FOREIGN KEY (createdUser) REFERENCES user_accounts (id)');
        $this->addSql('ALTER TABLE map_royalty ADD CONSTRAINT FK_640D0E59160C1387 FOREIGN KEY (modifiedUser) REFERENCES user_accounts (id)');
        $this->addSql('ALTER TABLE poi ADD CONSTRAINT FK_7DBB1FD6BF396750 FOREIGN KEY (id) REFERENCES activity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ride ADD CONSTRAINT FK_9B3D7CD0BF396750 FOREIGN KEY (id) REFERENCES activity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE route_point ADD CONSTRAINT FK_2ADAC18AAC74095A FOREIGN KEY (activity) REFERENCES activity (id)');
        $this->addSql('ALTER TABLE route_point ADD CONSTRAINT FK_2ADAC18A3E4AD1B3 FOREIGN KEY (direction) REFERENCES direction (id)');
        $this->addSql('ALTER TABLE route_point ADD CONSTRAINT FK_2ADAC18A7AF16D89 FOREIGN KEY (createdUser) REFERENCES user_accounts (id)');
        $this->addSql('ALTER TABLE route_point ADD CONSTRAINT FK_2ADAC18A160C1387 FOREIGN KEY (modifiedUser) REFERENCES user_accounts (id)');
        $this->addSql('ALTER TABLE tag ADD CONSTRAINT FK_389B7837AF16D89 FOREIGN KEY (createdUser) REFERENCES user_accounts (id)');
        $this->addSql('ALTER TABLE tag ADD CONSTRAINT FK_389B783160C1387 FOREIGN KEY (modifiedUser) REFERENCES user_accounts (id)');
        $this->addSql('ALTER TABLE tag_parent ADD CONSTRAINT FK_D4CF2963D5D1ECEE FOREIGN KEY (tag_a_id) REFERENCES tag (id)');
        $this->addSql('ALTER TABLE tag_parent ADD CONSTRAINT FK_D4CF2963C7644300 FOREIGN KEY (tag_b_id) REFERENCES tag (id)');
        $this->addSql('ALTER TABLE user_flag ADD CONSTRAINT FK_AB75A753AC74095A FOREIGN KEY (activity) REFERENCES activity (id)');
        $this->addSql('ALTER TABLE user_flag ADD CONSTRAINT FK_AB75A753DAA2006D FOREIGN KEY (flagType) REFERENCES flag_type (id)');
        $this->addSql('ALTER TABLE user_flag ADD CONSTRAINT FK_AB75A7537AF16D89 FOREIGN KEY (createdUser) REFERENCES user_accounts (id)');
        $this->addSql('ALTER TABLE user_flag ADD CONSTRAINT FK_AB75A753160C1387 FOREIGN KEY (modifiedUser) REFERENCES user_accounts (id)');
        $this->addSql('ALTER TABLE walk ADD CONSTRAINT FK_8D917A55BF396750 FOREIGN KEY (id) REFERENCES activity (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
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
        $this->addSql('ALTER TABLE activity_tag DROP CONSTRAINT FK_71B0290181C06096');
        $this->addSql('ALTER TABLE associated_activity DROP CONSTRAINT FK_DDDEC52C381A88CA');
        $this->addSql('ALTER TABLE associated_activity DROP CONSTRAINT FK_DDDEC52C98A168F1');
        $this->addSql('ALTER TABLE admin_notes DROP CONSTRAINT FK_A1315D76AC74095A');
        $this->addSql('ALTER TABLE collection_contents DROP CONSTRAINT FK_457ADF47AC74095A');
        $this->addSql('ALTER TABLE direction DROP CONSTRAINT FK_3E4AD1B3AC74095A');
        $this->addSql('ALTER TABLE drive DROP CONSTRAINT FK_681DF58FBF396750');
        $this->addSql('ALTER TABLE image DROP CONSTRAINT FK_C53D045FAC74095A');
        $this->addSql('ALTER TABLE map_royalty DROP CONSTRAINT FK_640D0E59AC74095A');
        $this->addSql('ALTER TABLE poi DROP CONSTRAINT FK_7DBB1FD6BF396750');
        $this->addSql('ALTER TABLE ride DROP CONSTRAINT FK_9B3D7CD0BF396750');
        $this->addSql('ALTER TABLE route_point DROP CONSTRAINT FK_2ADAC18AAC74095A');
        $this->addSql('ALTER TABLE user_flag DROP CONSTRAINT FK_AB75A753AC74095A');
        $this->addSql('ALTER TABLE walk DROP CONSTRAINT FK_8D917A55BF396750');
        $this->addSql('ALTER TABLE admin_notes DROP CONSTRAINT FK_A1315D76FC4D6532');
        $this->addSql('ALTER TABLE collection_tag DROP CONSTRAINT FK_AB0018E7514956FD');
        $this->addSql('ALTER TABLE collection_contents DROP CONSTRAINT FK_457ADF47FC4D6532');
        $this->addSql('ALTER TABLE route_point DROP CONSTRAINT FK_2ADAC18A3E4AD1B3');
        $this->addSql('ALTER TABLE user_flag DROP CONSTRAINT FK_AB75A753DAA2006D');
        $this->addSql('ALTER TABLE activity DROP CONSTRAINT FK_AC74095AF691CABA');
        $this->addSql('ALTER TABLE activity_tag DROP CONSTRAINT FK_71B02901BAD26311');
        $this->addSql('ALTER TABLE collection_tag DROP CONSTRAINT FK_AB0018E7BAD26311');
        $this->addSql('ALTER TABLE tag_parent DROP CONSTRAINT FK_D4CF2963D5D1ECEE');
        $this->addSql('ALTER TABLE tag_parent DROP CONSTRAINT FK_D4CF2963C7644300');
        $this->addSql('ALTER TABLE activity DROP CONSTRAINT FK_AC74095A7AF16D89');
        $this->addSql('ALTER TABLE activity DROP CONSTRAINT FK_AC74095A160C1387');
        $this->addSql('ALTER TABLE admin_notes DROP CONSTRAINT FK_A1315D767AF16D89');
        $this->addSql('ALTER TABLE admin_notes DROP CONSTRAINT FK_A1315D76160C1387');
        $this->addSql('ALTER TABLE collection DROP CONSTRAINT FK_FC4D65327AF16D89');
        $this->addSql('ALTER TABLE collection DROP CONSTRAINT FK_FC4D6532160C1387');
        $this->addSql('ALTER TABLE direction DROP CONSTRAINT FK_3E4AD1B37AF16D89');
        $this->addSql('ALTER TABLE direction DROP CONSTRAINT FK_3E4AD1B3160C1387');
        $this->addSql('ALTER TABLE image DROP CONSTRAINT FK_C53D045F7AF16D89');
        $this->addSql('ALTER TABLE image DROP CONSTRAINT FK_C53D045F160C1387');
        $this->addSql('ALTER TABLE map_royalty DROP CONSTRAINT FK_640D0E597AF16D89');
        $this->addSql('ALTER TABLE map_royalty DROP CONSTRAINT FK_640D0E59160C1387');
        $this->addSql('ALTER TABLE route_point DROP CONSTRAINT FK_2ADAC18A7AF16D89');
        $this->addSql('ALTER TABLE route_point DROP CONSTRAINT FK_2ADAC18A160C1387');
        $this->addSql('ALTER TABLE tag DROP CONSTRAINT FK_389B7837AF16D89');
        $this->addSql('ALTER TABLE tag DROP CONSTRAINT FK_389B783160C1387');
        $this->addSql('ALTER TABLE user_flag DROP CONSTRAINT FK_AB75A7537AF16D89');
        $this->addSql('ALTER TABLE user_flag DROP CONSTRAINT FK_AB75A753160C1387');
        $this->addSql('DROP TABLE activity');
        $this->addSql('DROP TABLE activity_tag');
        $this->addSql('DROP TABLE associated_activity');
        $this->addSql('DROP TABLE admin_notes');
        $this->addSql('DROP TABLE collection');
        $this->addSql('DROP TABLE collection_tag');
        $this->addSql('DROP TABLE collection_contents');
        $this->addSql('DROP TABLE direction');
        $this->addSql('DROP TABLE drive');
        $this->addSql('DROP TABLE flag_type');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE map_royalty');
        $this->addSql('DROP TABLE poi');
        $this->addSql('DROP TABLE ride');
        $this->addSql('DROP TABLE route_point');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE tag_parent');
        $this->addSql('DROP TABLE user_accounts');
        $this->addSql('DROP TABLE user_flag');
        $this->addSql('DROP TABLE walk');
    }
}
