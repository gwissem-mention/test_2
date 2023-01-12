<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230110155141 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Rename agent field to assigned_to';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE complaint RENAME COLUMN agent TO assigned_to');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE complaint RENAME COLUMN assigned_to TO agent');
    }
}
