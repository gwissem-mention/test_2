<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20221219160424 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add refusal fields';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE complaint ADD refusal_reason INT DEFAULT NULL');
        $this->addSql('ALTER TABLE complaint ADD refusal_text TEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE complaint DROP refusal_reason');
        $this->addSql('ALTER TABLE complaint DROP refusal_text');
    }
}
