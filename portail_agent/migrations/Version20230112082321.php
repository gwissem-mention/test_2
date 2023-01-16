<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230112082321 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Remove comments_number field in complaint table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE complaint DROP comments_number');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE complaint ADD comments_number INT NOT NULL');
    }
}
