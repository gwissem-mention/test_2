<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230808141523 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add owned, owner_lastname & owner_firstname field to multimedia_object';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE multimedia_object ADD owned BOOLEAN NOT NULL DEFAULT TRUE');
        $this->addSql('ALTER TABLE multimedia_object ADD owner_lastname VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE multimedia_object ADD owner_firstname VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE multimedia_object DROP owned');
        $this->addSql('ALTER TABLE multimedia_object DROP owner_lastname');
        $this->addSql('ALTER TABLE multimedia_object DROP owner_firstname');
    }
}
