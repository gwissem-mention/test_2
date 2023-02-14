<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230214092704 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add assoc between User and Complaint';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE complaint RENAME COLUMN assigned_to TO assigned_to_id');
        $this->addSql('ALTER TABLE complaint ADD CONSTRAINT FK_5F2732B5F4BD7827 FOREIGN KEY (assigned_to_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_5F2732B5F4BD7827 ON complaint (assigned_to_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE complaint DROP CONSTRAINT FK_5F2732B5F4BD7827');
        $this->addSql('DROP INDEX IDX_5F2732B5F4BD7827');
        $this->addSql('ALTER TABLE complaint RENAME COLUMN assigned_to_id TO assigned_to');
    }
}
