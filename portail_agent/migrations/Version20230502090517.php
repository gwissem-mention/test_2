<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230502090517 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add appointment_contact_information and appointment_time fields and remove not null constraint on appointment_date field (Complaint)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE complaint ADD appointment_contact_information VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE complaint ALTER appointment_date DROP NOT NULL');
        $this->addSql('ALTER TABLE complaint ADD appointment_time TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN complaint.appointment_time IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE complaint DROP appointment_contact_information');
        $this->addSql('ALTER TABLE complaint ALTER appointment_date SET NOT NULL');
        $this->addSql('ALTER TABLE complaint DROP appointment_time');
    }
}
