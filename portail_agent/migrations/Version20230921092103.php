<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230921092103 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add label field (Vehicle)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE vehicle ADD label VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE vehicle DROP label');
    }
}
