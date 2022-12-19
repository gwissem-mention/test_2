<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20221216164233 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Rename alert for alert_number';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE facts RENAME COLUMN alerts TO alert_number');
        $this->addSql('ALTER TABLE identity RENAME COLUMN alerts TO alert_number');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE identity RENAME COLUMN alert_number TO alerts');
        $this->addSql('ALTER TABLE facts RENAME COLUMN alert_number TO alerts');
    }
}
