<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230629153525 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add oodrive_report_folder column to complaint table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE complaint ADD oodrive_report_folder VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE complaint DROP oodrive_report_folder');
    }
}
