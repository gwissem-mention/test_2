<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230428092152 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Drop description column from additional_information table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE additional_information DROP description');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE additional_information ADD description TEXT NOT NULL');
    }
}
