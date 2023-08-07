<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230803134938 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add nature field to multimedia_object';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE multimedia_object ADD nature VARCHAR(255)');
        $this->addSql("UPDATE multimedia_object SET nature='Téléphone Portable'");
        $this->addSql('ALTER TABLE multimedia_object ALTER COLUMN nature SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE multimedia_object DROP nature');
    }
}
