<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230109141936 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Remove old assigned_agent field';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE complaint DROP assigned_agent');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE complaint ADD assigned_agent VARCHAR(255) NOT NULL');
    }
}
