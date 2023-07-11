<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230704171007 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'add appointment notification sent at field';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE complaint ADD appointment_notification_sent_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN complaint.appointment_notification_sent_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE complaint DROP appointment_notification_sent_at');
    }
}
