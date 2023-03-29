<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230324145155 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add relation between a Comment and his author (Comment)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE comment ADD author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comment DROP author');
        $this->addSql('UPDATE comment SET author_id = (SELECT id FROM "user" LIMIT 1)');
        $this->addSql('ALTER TABLE comment ALTER COLUMN author_id SET NOT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CF675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_9474526CF675F31B ON comment (author_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526CF675F31B');
        $this->addSql('DROP INDEX IDX_9474526CF675F31B');
        $this->addSql('ALTER TABLE comment ADD author VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE comment DROP author_id');
    }
}
