<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210115171143 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE post_int_seq CASCADE');
        $this->addSql('ALTER TABLE post RENAME COLUMN int TO id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE post_int_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('DROP INDEX post_pkey');
        $this->addSql('ALTER TABLE post RENAME COLUMN id TO "int"');
        $this->addSql('ALTER TABLE post ADD PRIMARY KEY (int)');
    }
}
