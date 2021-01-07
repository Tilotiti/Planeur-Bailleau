<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210107111422 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE aircraft (id SERIAL NOT NULL, license VARCHAR(255) NOT NULL, competition_number VARCHAR(255) NOT NULL, model VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE document (id SERIAL NOT NULL, aircraft_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D8698A76846E2F5C ON document (aircraft_id)');
        $this->addSql('CREATE TABLE menu (id SERIAL NOT NULL, "order" INT NOT NULL, public BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE menu_translation (id SERIAL NOT NULL, translatable_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, locale VARCHAR(5) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DC955B232C2AC5D3 ON menu_translation (translatable_id)');
        $this->addSql('CREATE UNIQUE INDEX menu_translation_unique_translation ON menu_translation (translatable_id, locale)');
        $this->addSql('CREATE TABLE page (id SERIAL NOT NULL, menu_id INT DEFAULT NULL, "order" INT NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_140AB620CCD7E912 ON page (menu_id)');
        $this->addSql('CREATE TABLE page_translation (id SERIAL NOT NULL, translatable_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, content TEXT NOT NULL, locale VARCHAR(5) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A3D51B1D2C2AC5D3 ON page_translation (translatable_id)');
        $this->addSql('CREATE UNIQUE INDEX page_translation_unique_translation ON page_translation (translatable_id, locale)');
        $this->addSql('CREATE TABLE post (int SERIAL NOT NULL, user_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, locale VARCHAR(255) NOT NULL, content TEXT NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, draft BOOLEAN NOT NULL, PRIMARY KEY(int))');
        $this->addSql('CREATE INDEX IDX_5A8A6C8DA76ED395 ON post (user_id)');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A76846E2F5C FOREIGN KEY (aircraft_id) REFERENCES aircraft (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE menu_translation ADD CONSTRAINT FK_DC955B232C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES menu (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE page ADD CONSTRAINT FK_140AB620CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE page_translation ADD CONSTRAINT FK_A3D51B1D2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES page (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE document DROP CONSTRAINT FK_D8698A76846E2F5C');
        $this->addSql('ALTER TABLE menu_translation DROP CONSTRAINT FK_DC955B232C2AC5D3');
        $this->addSql('ALTER TABLE page DROP CONSTRAINT FK_140AB620CCD7E912');
        $this->addSql('ALTER TABLE page_translation DROP CONSTRAINT FK_A3D51B1D2C2AC5D3');
        $this->addSql('DROP TABLE aircraft');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE menu');
        $this->addSql('DROP TABLE menu_translation');
        $this->addSql('DROP TABLE page');
        $this->addSql('DROP TABLE page_translation');
        $this->addSql('DROP TABLE post');
    }
}
