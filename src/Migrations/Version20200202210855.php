<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200202210855 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE direction (id INT AUTO_INCREMENT NOT NULL, activity INT NOT NULL, position INT NOT NULL, direction LONGTEXT NOT NULL, created_date DATETIME NOT NULL, modified_date DATETIME NOT NULL, modifiedUser INT NOT NULL, INDEX IDX_3E4AD1B3AC74095A (activity), INDEX IDX_3E4AD1B3160C1387 (modifiedUser), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE flag_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE collection_contents (id INT AUTO_INCREMENT NOT NULL, collection INT NOT NULL, activity INT NOT NULL, position INT NOT NULL, created_date DATETIME DEFAULT NULL, modified_date DATETIME DEFAULT NULL, modifiedUser INT NOT NULL, INDEX IDX_457ADF47FC4D6532 (collection), INDEX IDX_457ADF47AC74095A (activity), INDEX IDX_457ADF47160C1387 (modifiedUser), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(190) NOT NULL, created_date DATETIME NOT NULL, modified_date DATETIME NOT NULL, modifiedUser INT NOT NULL, UNIQUE INDEX UNIQ_389B7835E237E06 (name), INDEX IDX_389B783160C1387 (modifiedUser), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag_parent (tag_a_id INT NOT NULL, tag_b_id INT NOT NULL, INDEX IDX_D4CF2963D5D1ECEE (tag_a_id), INDEX IDX_D4CF2963C7644300 (tag_b_id), PRIMARY KEY(tag_a_id, tag_b_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE activity (id INT AUTO_INCREMENT NOT NULL, maproyalty INT DEFAULT NULL, name VARCHAR(190) NOT NULL, start_grid_ref VARCHAR(45) DEFAULT NULL, latitude NUMERIC(10, 8) DEFAULT NULL, longitude NUMERIC(11, 8) DEFAULT NULL, description LONGTEXT DEFAULT NULL, searchable_description LONGTEXT DEFAULT NULL, status INT DEFAULT 0 NOT NULL, online_friendly TINYINT(1) NOT NULL, slug VARCHAR(190) DEFAULT NULL, created_date DATETIME NOT NULL, modified_date DATETIME NOT NULL, modifiedUser INT NOT NULL, type VARCHAR(45) NOT NULL, UNIQUE INDEX UNIQ_AC74095A5E237E06 (name), INDEX IDX_AC74095A160C1387 (modifiedUser), UNIQUE INDEX UNIQ_AC74095AF691CABA (maproyalty), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE activity_tag (activity_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_71B0290181C06096 (activity_id), INDEX IDX_71B02901BAD26311 (tag_id), PRIMARY KEY(activity_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE associated_activity (activity_a_id INT NOT NULL, associated_b_id INT NOT NULL, INDEX IDX_DDDEC52C381A88CA (activity_a_id), INDEX IDX_DDDEC52C98A168F1 (associated_b_id), PRIMARY KEY(activity_a_id, associated_b_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE poi (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(190) NOT NULL, UNIQUE INDEX UNIQ_88BDF3E9F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE map_royalty (id INT AUTO_INCREMENT NOT NULL, activity INT NOT NULL, width DOUBLE PRECISION DEFAULT NULL, height DOUBLE PRECISION DEFAULT NULL, map_scale INT DEFAULT NULL, source_scale INT DEFAULT NULL, sea_area INT DEFAULT NULL, created_date DATETIME NOT NULL, modified_date DATETIME NOT NULL, modifiedUser INT NOT NULL, INDEX IDX_640D0E59AC74095A (activity), INDEX IDX_640D0E59160C1387 (modifiedUser), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_flag (id INT AUTO_INCREMENT NOT NULL, activity INT NOT NULL, description LONGTEXT DEFAULT NULL, created_date DATETIME NOT NULL, modified_date DATETIME NOT NULL, flagType INT NOT NULL, modifiedUser INT NOT NULL, INDEX IDX_AB75A753AC74095A (activity), INDEX IDX_AB75A753DAA2006D (flagType), INDEX IDX_AB75A753160C1387 (modifiedUser), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE walk (id INT NOT NULL, short_description LONGTEXT DEFAULT NULL, location LONGTEXT DEFAULT NULL, distance DOUBLE PRECISION DEFAULT NULL, minimum_time_hours INT DEFAULT NULL, minimum_time_minutes INT DEFAULT NULL, ascent INT DEFAULT NULL, gradient INT DEFAULT NULL, difficulty INT DEFAULT NULL, paths VARCHAR(500) DEFAULT NULL, landscape VARCHAR(500) DEFAULT NULL, finish_grid_ref VARCHAR(45) DEFAULT NULL, dog_friendliness LONGTEXT DEFAULT NULL, parking LONGTEXT DEFAULT NULL, public_toilet LONGTEXT DEFAULT NULL, notes LONGTEXT DEFAULT NULL, what_to_look_out_for LONGTEXT DEFAULT NULL, where_to_eat_and_drink LONGTEXT DEFAULT NULL, while_you_are_there LONGTEXT DEFAULT NULL, extension VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE drive (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE collection (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(190) NOT NULL, description LONGTEXT DEFAULT NULL, status VARCHAR(45) DEFAULT \'public\' NOT NULL, slug VARCHAR(255) NOT NULL, created_date DATETIME NOT NULL, modified_date DATETIME NOT NULL, modifiedUser INT NOT NULL, UNIQUE INDEX UNIQ_FC4D65325E237E06 (name), INDEX IDX_FC4D6532160C1387 (modifiedUser), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE collection_tag (collection_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_AB0018E7514956FD (collection_id), INDEX IDX_AB0018E7BAD26311 (tag_id), PRIMARY KEY(collection_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE route_point (id INT AUTO_INCREMENT NOT NULL, activity INT NOT NULL, direction INT DEFAULT NULL, route_order INT NOT NULL, latitude NUMERIC(10, 8) NOT NULL, longitude NUMERIC(11, 8) NOT NULL, created_date DATETIME NOT NULL, modified_date DATETIME NOT NULL, modifiedUser INT NOT NULL, INDEX IDX_2ADAC18AAC74095A (activity), UNIQUE INDEX UNIQ_2ADAC18A3E4AD1B3 (direction), INDEX IDX_2ADAC18A160C1387 (modifiedUser), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cycle (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE admin_notes (id INT AUTO_INCREMENT NOT NULL, collection INT DEFAULT NULL, activity INT DEFAULT NULL, note VARCHAR(255) NOT NULL, created_date DATETIME NOT NULL, modified_date DATETIME NOT NULL, modifiedUser INT NOT NULL, INDEX IDX_A1315D76FC4D6532 (collection), INDEX IDX_A1315D76AC74095A (activity), INDEX IDX_A1315D76160C1387 (modifiedUser), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, activity INT NOT NULL, name VARCHAR(190) NOT NULL, type VARCHAR(45) NOT NULL, created_date DATETIME NOT NULL, modified_date DATETIME NOT NULL, modifiedUser INT NOT NULL, INDEX IDX_C53D045FAC74095A (activity), INDEX IDX_C53D045F160C1387 (modifiedUser), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE direction ADD CONSTRAINT FK_3E4AD1B3AC74095A FOREIGN KEY (activity) REFERENCES activity (id)');
        $this->addSql('ALTER TABLE direction ADD CONSTRAINT FK_3E4AD1B3160C1387 FOREIGN KEY (modifiedUser) REFERENCES app_user (id)');
        $this->addSql('ALTER TABLE collection_contents ADD CONSTRAINT FK_457ADF47FC4D6532 FOREIGN KEY (collection) REFERENCES collection (id)');
        $this->addSql('ALTER TABLE collection_contents ADD CONSTRAINT FK_457ADF47AC74095A FOREIGN KEY (activity) REFERENCES activity (id)');
        $this->addSql('ALTER TABLE collection_contents ADD CONSTRAINT FK_457ADF47160C1387 FOREIGN KEY (modifiedUser) REFERENCES app_user (id)');
        $this->addSql('ALTER TABLE tag ADD CONSTRAINT FK_389B783160C1387 FOREIGN KEY (modifiedUser) REFERENCES app_user (id)');
        $this->addSql('ALTER TABLE tag_parent ADD CONSTRAINT FK_D4CF2963D5D1ECEE FOREIGN KEY (tag_a_id) REFERENCES tag (id)');
        $this->addSql('ALTER TABLE tag_parent ADD CONSTRAINT FK_D4CF2963C7644300 FOREIGN KEY (tag_b_id) REFERENCES tag (id)');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095A160C1387 FOREIGN KEY (modifiedUser) REFERENCES app_user (id)');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095AF691CABA FOREIGN KEY (maproyalty) REFERENCES map_royalty (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activity_tag ADD CONSTRAINT FK_71B0290181C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activity_tag ADD CONSTRAINT FK_71B02901BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE associated_activity ADD CONSTRAINT FK_DDDEC52C381A88CA FOREIGN KEY (activity_a_id) REFERENCES activity (id)');
        $this->addSql('ALTER TABLE associated_activity ADD CONSTRAINT FK_DDDEC52C98A168F1 FOREIGN KEY (associated_b_id) REFERENCES activity (id)');
        $this->addSql('ALTER TABLE poi ADD CONSTRAINT FK_7DBB1FD6BF396750 FOREIGN KEY (id) REFERENCES activity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE map_royalty ADD CONSTRAINT FK_640D0E59AC74095A FOREIGN KEY (activity) REFERENCES activity (id)');
        $this->addSql('ALTER TABLE map_royalty ADD CONSTRAINT FK_640D0E59160C1387 FOREIGN KEY (modifiedUser) REFERENCES app_user (id)');
        $this->addSql('ALTER TABLE user_flag ADD CONSTRAINT FK_AB75A753AC74095A FOREIGN KEY (activity) REFERENCES activity (id)');
        $this->addSql('ALTER TABLE user_flag ADD CONSTRAINT FK_AB75A753DAA2006D FOREIGN KEY (flagType) REFERENCES flag_type (id)');
        $this->addSql('ALTER TABLE user_flag ADD CONSTRAINT FK_AB75A753160C1387 FOREIGN KEY (modifiedUser) REFERENCES app_user (id)');
        $this->addSql('ALTER TABLE walk ADD CONSTRAINT FK_8D917A55BF396750 FOREIGN KEY (id) REFERENCES activity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE drive ADD CONSTRAINT FK_681DF58FBF396750 FOREIGN KEY (id) REFERENCES activity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE collection ADD CONSTRAINT FK_FC4D6532160C1387 FOREIGN KEY (modifiedUser) REFERENCES app_user (id)');
        $this->addSql('ALTER TABLE collection_tag ADD CONSTRAINT FK_AB0018E7514956FD FOREIGN KEY (collection_id) REFERENCES collection (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE collection_tag ADD CONSTRAINT FK_AB0018E7BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE route_point ADD CONSTRAINT FK_2ADAC18AAC74095A FOREIGN KEY (activity) REFERENCES activity (id)');
        $this->addSql('ALTER TABLE route_point ADD CONSTRAINT FK_2ADAC18A3E4AD1B3 FOREIGN KEY (direction) REFERENCES direction (id)');
        $this->addSql('ALTER TABLE route_point ADD CONSTRAINT FK_2ADAC18A160C1387 FOREIGN KEY (modifiedUser) REFERENCES app_user (id)');
        $this->addSql('ALTER TABLE cycle ADD CONSTRAINT FK_B086D193BF396750 FOREIGN KEY (id) REFERENCES activity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE admin_notes ADD CONSTRAINT FK_A1315D76FC4D6532 FOREIGN KEY (collection) REFERENCES collection (id)');
        $this->addSql('ALTER TABLE admin_notes ADD CONSTRAINT FK_A1315D76AC74095A FOREIGN KEY (activity) REFERENCES activity (id)');
        $this->addSql('ALTER TABLE admin_notes ADD CONSTRAINT FK_A1315D76160C1387 FOREIGN KEY (modifiedUser) REFERENCES app_user (id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FAC74095A FOREIGN KEY (activity) REFERENCES activity (id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F160C1387 FOREIGN KEY (modifiedUser) REFERENCES app_user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE route_point DROP FOREIGN KEY FK_2ADAC18A3E4AD1B3');
        $this->addSql('ALTER TABLE user_flag DROP FOREIGN KEY FK_AB75A753DAA2006D');
        $this->addSql('ALTER TABLE tag_parent DROP FOREIGN KEY FK_D4CF2963D5D1ECEE');
        $this->addSql('ALTER TABLE tag_parent DROP FOREIGN KEY FK_D4CF2963C7644300');
        $this->addSql('ALTER TABLE activity_tag DROP FOREIGN KEY FK_71B02901BAD26311');
        $this->addSql('ALTER TABLE collection_tag DROP FOREIGN KEY FK_AB0018E7BAD26311');
        $this->addSql('ALTER TABLE direction DROP FOREIGN KEY FK_3E4AD1B3AC74095A');
        $this->addSql('ALTER TABLE collection_contents DROP FOREIGN KEY FK_457ADF47AC74095A');
        $this->addSql('ALTER TABLE activity_tag DROP FOREIGN KEY FK_71B0290181C06096');
        $this->addSql('ALTER TABLE associated_activity DROP FOREIGN KEY FK_DDDEC52C381A88CA');
        $this->addSql('ALTER TABLE associated_activity DROP FOREIGN KEY FK_DDDEC52C98A168F1');
        $this->addSql('ALTER TABLE poi DROP FOREIGN KEY FK_7DBB1FD6BF396750');
        $this->addSql('ALTER TABLE map_royalty DROP FOREIGN KEY FK_640D0E59AC74095A');
        $this->addSql('ALTER TABLE user_flag DROP FOREIGN KEY FK_AB75A753AC74095A');
        $this->addSql('ALTER TABLE walk DROP FOREIGN KEY FK_8D917A55BF396750');
        $this->addSql('ALTER TABLE drive DROP FOREIGN KEY FK_681DF58FBF396750');
        $this->addSql('ALTER TABLE route_point DROP FOREIGN KEY FK_2ADAC18AAC74095A');
        $this->addSql('ALTER TABLE cycle DROP FOREIGN KEY FK_B086D193BF396750');
        $this->addSql('ALTER TABLE admin_notes DROP FOREIGN KEY FK_A1315D76AC74095A');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FAC74095A');
        $this->addSql('ALTER TABLE direction DROP FOREIGN KEY FK_3E4AD1B3160C1387');
        $this->addSql('ALTER TABLE collection_contents DROP FOREIGN KEY FK_457ADF47160C1387');
        $this->addSql('ALTER TABLE tag DROP FOREIGN KEY FK_389B783160C1387');
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095A160C1387');
        $this->addSql('ALTER TABLE map_royalty DROP FOREIGN KEY FK_640D0E59160C1387');
        $this->addSql('ALTER TABLE user_flag DROP FOREIGN KEY FK_AB75A753160C1387');
        $this->addSql('ALTER TABLE collection DROP FOREIGN KEY FK_FC4D6532160C1387');
        $this->addSql('ALTER TABLE route_point DROP FOREIGN KEY FK_2ADAC18A160C1387');
        $this->addSql('ALTER TABLE admin_notes DROP FOREIGN KEY FK_A1315D76160C1387');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F160C1387');
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095AF691CABA');
        $this->addSql('ALTER TABLE collection_contents DROP FOREIGN KEY FK_457ADF47FC4D6532');
        $this->addSql('ALTER TABLE collection_tag DROP FOREIGN KEY FK_AB0018E7514956FD');
        $this->addSql('ALTER TABLE admin_notes DROP FOREIGN KEY FK_A1315D76FC4D6532');
        $this->addSql('DROP TABLE direction');
        $this->addSql('DROP TABLE flag_type');
        $this->addSql('DROP TABLE collection_contents');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE tag_parent');
        $this->addSql('DROP TABLE activity');
        $this->addSql('DROP TABLE activity_tag');
        $this->addSql('DROP TABLE associated_activity');
        $this->addSql('DROP TABLE poi');
        $this->addSql('DROP TABLE app_user');
        $this->addSql('DROP TABLE map_royalty');
        $this->addSql('DROP TABLE user_flag');
        $this->addSql('DROP TABLE walk');
        $this->addSql('DROP TABLE drive');
        $this->addSql('DROP TABLE collection');
        $this->addSql('DROP TABLE collection_tag');
        $this->addSql('DROP TABLE route_point');
        $this->addSql('DROP TABLE cycle');
        $this->addSql('DROP TABLE admin_notes');
        $this->addSql('DROP TABLE image');
    }
}
