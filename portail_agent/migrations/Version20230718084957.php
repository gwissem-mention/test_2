<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230718084957 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add appointment_required and set consent_contact_electronics to NOT NULL';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE complaint ADD appointment_required BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE complaint ALTER consent_contact_electronics SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE complaint DROP appointment_required');
        $this->addSql('ALTER TABLE complaint ALTER consent_contact_electronics DROP NOT NULL');
    }
}
