<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220613134200 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer ADD first_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE customer ADD laste_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE customer DROP firstname');
        $this->addSql('ALTER TABLE customer DROP lastename');
        $this->addSql('ALTER TABLE customer RENAME COLUMN ccompany TO company');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE customer ADD firstname VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE customer ADD lastename VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE customer DROP first_name');
        $this->addSql('ALTER TABLE customer DROP laste_name');
        $this->addSql('ALTER TABLE customer RENAME COLUMN company TO ccompany');
    }
}
