<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230222151301 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add processing deadline field (Complaint) and urgent field (Notification)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE complaint ADD processing_deadline TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('COMMENT ON COLUMN complaint.processing_deadline IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE complaint ADD deadline_notified BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE notification ADD urgent BOOLEAN NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE complaint DROP processing_deadline');
        $this->addSql('ALTER TABLE complaint DROP deadline_notified');
        $this->addSql('ALTER TABLE notification DROP urgent');
    }
}
