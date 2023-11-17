<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231116131937 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add unit_assigned_institution to complaint table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE complaint ADD unit_assigned_institution VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE complaint DROP unit_assigned_institution');
    }
}
