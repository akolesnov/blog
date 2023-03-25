<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230325165313 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE post_categories (post_id UUID NOT NULL, category_id UUID NOT NULL, PRIMARY KEY(post_id, category_id))');
        $this->addSql('CREATE INDEX IDX_198B4FA94B89032C ON post_categories (post_id)');
        $this->addSql('CREATE INDEX IDX_198B4FA912469DE2 ON post_categories (category_id)');
        $this->addSql('COMMENT ON COLUMN post_categories.post_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN post_categories.category_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE post_categories ADD CONSTRAINT FK_198B4FA94B89032C FOREIGN KEY (post_id) REFERENCES posts (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE post_categories ADD CONSTRAINT FK_198B4FA912469DE2 FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE post_categories DROP CONSTRAINT FK_198B4FA94B89032C');
        $this->addSql('ALTER TABLE post_categories DROP CONSTRAINT FK_198B4FA912469DE2');
        $this->addSql('DROP TABLE post_categories');
    }
}
