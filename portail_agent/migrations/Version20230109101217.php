<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230109101217 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add agent field';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE complaint ADD agent INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE complaint DROP agent');
    }
}
