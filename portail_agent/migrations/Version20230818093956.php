<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230818093956 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add issuing_country field (AdministrativeDocument)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE administrative_document ADD issuing_country VARCHAR(255)');
        $this->addSql("UPDATE administrative_document SET issuing_country='France'");
        $this->addSql('ALTER TABLE administrative_document ALTER COLUMN issuing_country SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE administrative_document DROP issuing_country');
    }
}
