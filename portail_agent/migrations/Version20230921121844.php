<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230921121844 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add quantity to simple_object';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE simple_object ADD quantity INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE simple_object DROP quantity');
    }
}
