<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230519090337 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add unit_reassignment_asked boolean field (Complaint)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE complaint ADD unit_reassignment_asked BOOLEAN NOT NULL DEFAULT FALSE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE complaint DROP unit_reassignment_asked');
    }
}
