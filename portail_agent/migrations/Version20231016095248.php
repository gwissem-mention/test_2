<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231016095248 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Delete option_notification';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE complaint DROP optin_notification');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE complaint ADD optin_notification BOOLEAN NOT NULL');
    }
}
