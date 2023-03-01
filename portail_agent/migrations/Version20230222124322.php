<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230222124322 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add Vehicle';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE vehicle (id INT NOT NULL, label VARCHAR(255) NOT NULL, brand VARCHAR(255) NOT NULL, model VARCHAR(255) DEFAULT NULL, registration_number VARCHAR(255) DEFAULT NULL, registration_country VARCHAR(255) DEFAULT NULL, insurance_company VARCHAR(255) DEFAULT NULL, insurance_number VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E486BF396750 FOREIGN KEY (id) REFERENCES object (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE vehicle DROP CONSTRAINT FK_1B80E486BF396750');
        $this->addSql('DROP TABLE vehicle');
    }
}
