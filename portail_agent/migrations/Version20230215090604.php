<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230215090604 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Reverse adding missing fields for XML file generation (AdministrativeDocument, MultimediaObject)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE administrative_document DROP issuing_country');
        $this->addSql('ALTER TABLE administrative_document DROP number');
        $this->addSql('ALTER TABLE administrative_document DROP delivery_date');
        $this->addSql('ALTER TABLE administrative_document DROP authority');
        $this->addSql('ALTER TABLE administrative_document DROP description');
        $this->addSql('ALTER TABLE multimedia_object DROP opposition');
        $this->addSql('ALTER TABLE multimedia_object DROP sim_number');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE multimedia_object ADD opposition BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE multimedia_object ADD sim_number VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE administrative_document ADD issuing_country VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE administrative_document ADD number VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE administrative_document ADD delivery_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE administrative_document ADD authority VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE administrative_document ADD description VARCHAR(255) NOT NULL');
        $this->addSql('COMMENT ON COLUMN administrative_document.delivery_date IS \'(DC2Type:datetime_immutable)\'');
    }
}
