<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230327150119 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add Corporation table and relation to Complaint';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE corporation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE corporation (id INT NOT NULL, siren_number VARCHAR(255) NOT NULL, company_name VARCHAR(255) NOT NULL, declarant_position VARCHAR(255) NOT NULL, nationality VARCHAR(255) NOT NULL, contact_email VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, alert_number INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE complaint ADD corporation_represented_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE complaint ADD CONSTRAINT FK_5F2732B5AC8F5151 FOREIGN KEY (corporation_represented_id) REFERENCES corporation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5F2732B5AC8F5151 ON complaint (corporation_represented_id)');
        $this->addSql('ALTER TABLE corporation ADD department VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE corporation ADD department_number INT NOT NULL');
        $this->addSql('ALTER TABLE corporation ADD city VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE corporation ADD post_code VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE corporation ADD insee_code VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE corporation ADD street_number INT NOT NULL');
        $this->addSql('ALTER TABLE corporation ADD street_type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE corporation ADD street_name VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE complaint DROP CONSTRAINT FK_5F2732B5AC8F5151');
        $this->addSql('DROP SEQUENCE corporation_id_seq CASCADE');
        $this->addSql('DROP TABLE corporation');
        $this->addSql('DROP INDEX UNIQ_5F2732B5AC8F5151');
        $this->addSql('ALTER TABLE complaint DROP corporation_represented_id');
    }
}
