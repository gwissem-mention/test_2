<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230320140802 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add unit reassignment fields (Complaint)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE complaint ADD unit_to_reassign VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE complaint ADD unit_reassign_text VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE complaint DROP unit_to_reassign');
        $this->addSql('ALTER TABLE complaint DROP unit_reassign_text');
    }
}
