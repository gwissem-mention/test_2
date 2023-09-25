<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230922143410 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add consent contact fields to complaint, remove consent_contact_electronics';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE complaint ADD consent_contact_email BOOLEAN DEFAULT false NOT NULL');
        $this->addSql('ALTER TABLE complaint ADD consent_contact_sms BOOLEAN DEFAULT false NOT NULL');
        $this->addSql('ALTER TABLE complaint ADD consent_contact_portal BOOLEAN DEFAULT false NOT NULL');
        $this->addSql('ALTER TABLE complaint DROP consent_contact_electronics');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE complaint ADD consent_contact_electronics BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE complaint DROP consent_contact_email');
        $this->addSql('ALTER TABLE complaint DROP consent_contact_sms');
        $this->addSql('ALTER TABLE complaint DROP consent_contact_portal');
    }
}
