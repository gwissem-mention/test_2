<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230821143835 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add serial number to simple object';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE simple_object ADD serial_number VARCHAR(20) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE simple_object DROP serial_number');
    }
}
