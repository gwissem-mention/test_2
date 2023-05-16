<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230512111054 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add title field (Comment)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE comment ADD title VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE comment DROP title');
    }
}
