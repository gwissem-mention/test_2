<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230208084325 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Update Identity entity additional fields';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE identity ADD home_phone VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE identity ADD office_phone VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE identity RENAME COLUMN phone TO mobile_phone');
        $this->addSql('ALTER TABLE identity ADD relationship_with_victime VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE identity ADD married_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE identity ADD family_status VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE identity ADD birth_postal_code VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE identity ADD birth_insee_code VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE identity ADD birth_department_number INT NOT NULL');
        $this->addSql('ALTER TABLE identity ADD address_street_number VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE identity ADD address_street_type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE identity ADD address_street_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE identity ADD address_insee_code VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE identity ADD address_country VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE identity ADD address_department VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE identity ADD address_department_number INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE identity ADD phone VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE identity DROP mobile_phone');
        $this->addSql('ALTER TABLE identity DROP home_phone');
        $this->addSql('ALTER TABLE identity DROP office_phone');
        $this->addSql('ALTER TABLE identity DROP relationship_with_victime');
        $this->addSql('ALTER TABLE identity DROP married_name');
        $this->addSql('ALTER TABLE identity DROP family_status');
        $this->addSql('ALTER TABLE identity DROP birth_postal_code');
        $this->addSql('ALTER TABLE identity DROP birth_insee_code');
        $this->addSql('ALTER TABLE identity DROP birth_department_number');
        $this->addSql('ALTER TABLE identity DROP address_street_number');
        $this->addSql('ALTER TABLE identity DROP address_street_type');
        $this->addSql('ALTER TABLE identity DROP address_street_name');
        $this->addSql('ALTER TABLE identity DROP address_insee_code');
        $this->addSql('ALTER TABLE identity DROP address_country');
        $this->addSql('ALTER TABLE identity DROP address_department');
        $this->addSql('ALTER TABLE identity DROP address_department_number');
    }
}
