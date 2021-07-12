<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210712093556 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE logs (id INT NOT NULL, dt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, success BOOLEAN NOT NULL, error VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE magazines (id INT NOT NULL, title VARCHAR(255) NOT NULL, annotation TEXT DEFAULT NULL, publisher_name TEXT DEFAULT NULL, publication_code VARCHAR(255) NOT NULL, quality DOUBLE PRECISION DEFAULT NULL, publisher_legal_address VARCHAR(255) DEFAULT NULL, age_category VARCHAR(255) DEFAULT NULL, mass_media_reg_num VARCHAR(255) DEFAULT NULL, mass_media_reg_date VARCHAR(255) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, data JSON NOT NULL, price NUMERIC(20, 2) DEFAULT NULL, pages INT DEFAULT NULL, weight NUMERIC(20, 2) DEFAULT NULL, PRIMARY KEY(id))');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE magazines_themes (magazine_id INT NOT NULL, theme_id INT NOT NULL, PRIMARY KEY(magazine_id, theme_id))');
        $this->addSql('CREATE INDEX idx_72fc91e259027487 ON magazines_themes (theme_id)');
        $this->addSql('CREATE INDEX idx_72fc91e23eb84a1d ON magazines_themes (magazine_id)');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE themes (id INT NOT NULL, name VARCHAR(255) NOT NULL, external_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE SEQUENCE logs_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE logs');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE magazines');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE magazines_themes');
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE themes');
    }
}
