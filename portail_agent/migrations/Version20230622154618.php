<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230622154618 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add oodrive_cleaned_up_declaration_at, rejected_at, closed_at, oodrive_cleaned_up_report_at to complaint';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE complaint ADD oodrive_cleaned_up_declaration_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE complaint ADD rejected_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE complaint ADD closed_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE complaint ADD oodrive_cleaned_up_report_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE complaint ADD oodrive_cleaned_up_attachments_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN complaint.oodrive_cleaned_up_declaration_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN complaint.rejected_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN complaint.closed_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN complaint.oodrive_cleaned_up_report_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN complaint.oodrive_cleaned_up_attachments_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE complaint DROP oodrive_cleaned_up_declaration_at');
        $this->addSql('ALTER TABLE complaint DROP rejected_at');
        $this->addSql('ALTER TABLE complaint DROP closed_at');
        $this->addSql('ALTER TABLE complaint DROP oodrive_cleaned_up_report_at');
        $this->addSql('ALTER TABLE complaint DROP oodrive_cleaned_up_attachments_at');
    }
}
