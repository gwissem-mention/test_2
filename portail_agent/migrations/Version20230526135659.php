<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230526135659 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add document owner information (AdministrativeDocument)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE administrative_document ADD owned BOOLEAN NOT NULL DEFAULT TRUE');
        $this->addSql('ALTER TABLE administrative_document ADD owner_lastname VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE administrative_document ADD owner_firstname VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE administrative_document ADD owner_company VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE administrative_document ADD owner_phone VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE administrative_document ADD owner_email VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE administrative_document ADD owner_address VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE administrative_document ADD number VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE administrative_document ADD issued_by VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE administrative_document ADD issued_on DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE administrative_document ADD validity_end_date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE administrative_document ADD owner_address_street_type VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE administrative_document ADD owner_address_street_number VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE administrative_document ADD owner_address_street_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE administrative_document ADD owner_address_insee_code VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE administrative_document ADD owner_address_postcode VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE administrative_document ADD owner_address_city VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE administrative_document ADD owner_address_department_number VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE administrative_document ADD owner_address_department VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE administrative_document DROP owned');
        $this->addSql('ALTER TABLE administrative_document DROP owner_lastname');
        $this->addSql('ALTER TABLE administrative_document DROP owner_firstname');
        $this->addSql('ALTER TABLE administrative_document DROP owner_company');
        $this->addSql('ALTER TABLE administrative_document DROP owner_phone');
        $this->addSql('ALTER TABLE administrative_document DROP owner_email');
        $this->addSql('ALTER TABLE administrative_document DROP owner_address');
        $this->addSql('ALTER TABLE administrative_document DROP number');
        $this->addSql('ALTER TABLE administrative_document DROP issued_by');
        $this->addSql('ALTER TABLE administrative_document DROP issued_on');
        $this->addSql('ALTER TABLE administrative_document DROP validity_end_date');
        $this->addSql('ALTER TABLE administrative_document DROP owner_address_street_type');
        $this->addSql('ALTER TABLE administrative_document DROP owner_address_street_number');
        $this->addSql('ALTER TABLE administrative_document DROP owner_address_street_name');
        $this->addSql('ALTER TABLE administrative_document DROP owner_address_insee_code');
        $this->addSql('ALTER TABLE administrative_document DROP owner_address_postcode');
        $this->addSql('ALTER TABLE administrative_document DROP owner_address_city');
        $this->addSql('ALTER TABLE administrative_document DROP owner_address_department_number');
        $this->addSql('ALTER TABLE administrative_document DROP owner_address_department');
    }
}
