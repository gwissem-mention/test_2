<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230426101505 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Set declarant_status to nullable';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE identity ALTER declarant_status DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE identity ALTER declarant_status SET NOT NULL');
    }
}
