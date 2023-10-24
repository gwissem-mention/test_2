<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231023093040 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add imei field to multimedia_object table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE multimedia_object ADD imei VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE multimedia_object DROP imei');
    }
}
