<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230822145102 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'add appointment_asked field (Complaint)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE complaint ADD appointment_asked BOOLEAN DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE complaint DROP appointment_asked');
    }
}
