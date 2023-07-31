<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230726125008 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add appointment_salesforce_notification_sent_at column to complaint table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE complaint ADD appointment_salesforce_notification_sent_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN complaint.appointment_salesforce_notification_sent_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE complaint DROP appointment_salesforce_notification_sent_at');
    }
}
