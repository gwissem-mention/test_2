<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230217135344 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add unit_assigned field (Complaint)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE complaint ADD unit_assigned VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE complaint DROP unit_assigned');
    }
}
