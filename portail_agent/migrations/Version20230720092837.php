<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230720092837 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add appointment_cancellation_counter field';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE complaint ADD appointment_cancellation_counter INT DEFAULT 0 NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE complaint DROP appointment_cancellation_counter');
    }
}
