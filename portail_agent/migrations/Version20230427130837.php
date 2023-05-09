<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230427130837 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add files column to object table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE object ADD files TEXT DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN object.files IS \'(DC2Type:simple_array)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE object DROP files');
    }
}
