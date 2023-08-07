<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230804085736 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add description field to multimedia_object';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE multimedia_object ADD description VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE multimedia_object DROP description');
    }
}
