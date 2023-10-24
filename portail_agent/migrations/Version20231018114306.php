<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231018114306 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add subtitle to Comment entity';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE comment ADD subtitle VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE comment DROP subtitle');
    }
}
