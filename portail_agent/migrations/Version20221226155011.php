<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20221226155011 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add birth_city property to the Identity table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE identity ADD birth_city VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE identity DROP birth_city');
    }
}
