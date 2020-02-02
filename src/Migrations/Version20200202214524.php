<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200202214524 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE activity ADD createdUser INT NOT NULL');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095A7AF16D89 FOREIGN KEY (createdUser) REFERENCES app_user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AC74095A989D9B62 ON activity (slug)');
        $this->addSql('CREATE INDEX IDX_AC74095A7AF16D89 ON activity (createdUser)');
        $this->addSql('ALTER TABLE direction ADD createdUser INT NOT NULL');
        $this->addSql('ALTER TABLE direction ADD CONSTRAINT FK_3E4AD1B37AF16D89 FOREIGN KEY (createdUser) REFERENCES app_user (id)');
        $this->addSql('CREATE INDEX IDX_3E4AD1B37AF16D89 ON direction (createdUser)');
        $this->addSql('ALTER TABLE collection_contents DROP FOREIGN KEY FK_457ADF47160C1387');
        $this->addSql('DROP INDEX IDX_457ADF47160C1387 ON collection_contents');
        $this->addSql('ALTER TABLE collection_contents DROP created_date, DROP modified_date, DROP modifiedUser');
        $this->addSql('ALTER TABLE tag ADD createdUser INT NOT NULL');
        $this->addSql('ALTER TABLE tag ADD CONSTRAINT FK_389B7837AF16D89 FOREIGN KEY (createdUser) REFERENCES app_user (id)');
        $this->addSql('CREATE INDEX IDX_389B7837AF16D89 ON tag (createdUser)');
        $this->addSql('ALTER TABLE map_royalty ADD createdUser INT NOT NULL');
        $this->addSql('ALTER TABLE map_royalty ADD CONSTRAINT FK_640D0E597AF16D89 FOREIGN KEY (createdUser) REFERENCES app_user (id)');
        $this->addSql('CREATE INDEX IDX_640D0E597AF16D89 ON map_royalty (createdUser)');
        $this->addSql('ALTER TABLE user_flag ADD createdUser INT NOT NULL');
        $this->addSql('ALTER TABLE user_flag ADD CONSTRAINT FK_AB75A7537AF16D89 FOREIGN KEY (createdUser) REFERENCES app_user (id)');
        $this->addSql('CREATE INDEX IDX_AB75A7537AF16D89 ON user_flag (createdUser)');
        $this->addSql('ALTER TABLE collection ADD createdUser INT NOT NULL');
        $this->addSql('ALTER TABLE collection ADD CONSTRAINT FK_FC4D65327AF16D89 FOREIGN KEY (createdUser) REFERENCES app_user (id)');
        $this->addSql('CREATE INDEX IDX_FC4D65327AF16D89 ON collection (createdUser)');
        $this->addSql('ALTER TABLE route_point ADD createdUser INT NOT NULL');
        $this->addSql('ALTER TABLE route_point ADD CONSTRAINT FK_2ADAC18A7AF16D89 FOREIGN KEY (createdUser) REFERENCES app_user (id)');
        $this->addSql('CREATE INDEX IDX_2ADAC18A7AF16D89 ON route_point (createdUser)');
        $this->addSql('ALTER TABLE admin_notes ADD createdUser INT NOT NULL');
        $this->addSql('ALTER TABLE admin_notes ADD CONSTRAINT FK_A1315D767AF16D89 FOREIGN KEY (createdUser) REFERENCES app_user (id)');
        $this->addSql('CREATE INDEX IDX_A1315D767AF16D89 ON admin_notes (createdUser)');
        $this->addSql('ALTER TABLE image ADD createdUser INT NOT NULL');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F7AF16D89 FOREIGN KEY (createdUser) REFERENCES app_user (id)');
        $this->addSql('CREATE INDEX IDX_C53D045F7AF16D89 ON image (createdUser)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095A7AF16D89');
        $this->addSql('DROP INDEX UNIQ_AC74095A989D9B62 ON activity');
        $this->addSql('DROP INDEX IDX_AC74095A7AF16D89 ON activity');
        $this->addSql('ALTER TABLE activity DROP createdUser');
        $this->addSql('ALTER TABLE admin_notes DROP FOREIGN KEY FK_A1315D767AF16D89');
        $this->addSql('DROP INDEX IDX_A1315D767AF16D89 ON admin_notes');
        $this->addSql('ALTER TABLE admin_notes DROP createdUser');
        $this->addSql('ALTER TABLE collection DROP FOREIGN KEY FK_FC4D65327AF16D89');
        $this->addSql('DROP INDEX IDX_FC4D65327AF16D89 ON collection');
        $this->addSql('ALTER TABLE collection DROP createdUser');
        $this->addSql('ALTER TABLE collection_contents ADD created_date DATETIME DEFAULT NULL, ADD modified_date DATETIME DEFAULT NULL, ADD modifiedUser INT NOT NULL');
        $this->addSql('ALTER TABLE collection_contents ADD CONSTRAINT FK_457ADF47160C1387 FOREIGN KEY (modifiedUser) REFERENCES app_user (id)');
        $this->addSql('CREATE INDEX IDX_457ADF47160C1387 ON collection_contents (modifiedUser)');
        $this->addSql('ALTER TABLE direction DROP FOREIGN KEY FK_3E4AD1B37AF16D89');
        $this->addSql('DROP INDEX IDX_3E4AD1B37AF16D89 ON direction');
        $this->addSql('ALTER TABLE direction DROP createdUser');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F7AF16D89');
        $this->addSql('DROP INDEX IDX_C53D045F7AF16D89 ON image');
        $this->addSql('ALTER TABLE image DROP createdUser');
        $this->addSql('ALTER TABLE map_royalty DROP FOREIGN KEY FK_640D0E597AF16D89');
        $this->addSql('DROP INDEX IDX_640D0E597AF16D89 ON map_royalty');
        $this->addSql('ALTER TABLE map_royalty DROP createdUser');
        $this->addSql('ALTER TABLE route_point DROP FOREIGN KEY FK_2ADAC18A7AF16D89');
        $this->addSql('DROP INDEX IDX_2ADAC18A7AF16D89 ON route_point');
        $this->addSql('ALTER TABLE route_point DROP createdUser');
        $this->addSql('ALTER TABLE tag DROP FOREIGN KEY FK_389B7837AF16D89');
        $this->addSql('DROP INDEX IDX_389B7837AF16D89 ON tag');
        $this->addSql('ALTER TABLE tag DROP createdUser');
        $this->addSql('ALTER TABLE user_flag DROP FOREIGN KEY FK_AB75A7537AF16D89');
        $this->addSql('DROP INDEX IDX_AB75A7537AF16D89 ON user_flag');
        $this->addSql('ALTER TABLE user_flag DROP createdUser');
    }
}
