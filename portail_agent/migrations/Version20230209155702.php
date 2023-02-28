<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230209155702 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add administrative document entity';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE administrative_document_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE administrative_document (id INT NOT NULL, victim_identity_id INT DEFAULT NULL, complaint_id INT NOT NULL, issuing_country VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, number VARCHAR(255) NOT NULL, delivery_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, authority VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, belongs_to_the_victim BOOLEAN NOT NULL, thief_from_vehicule BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_71CBFD049FF7D899 ON administrative_document (victim_identity_id)');
        $this->addSql('CREATE INDEX IDX_71CBFD04EDAE188E ON administrative_document (complaint_id)');
        $this->addSql('COMMENT ON COLUMN administrative_document.delivery_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE administrative_document ADD CONSTRAINT FK_71CBFD049FF7D899 FOREIGN KEY (victim_identity_id) REFERENCES identity (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE administrative_document ADD CONSTRAINT FK_71CBFD04EDAE188E FOREIGN KEY (complaint_id) REFERENCES complaint (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE administrative_document ADD alert_number INT DEFAULT NULL');
        $this->addSql('ALTER TABLE administrative_document ADD amount DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE administrative_document_id_seq CASCADE');
        $this->addSql('ALTER TABLE administrative_document DROP CONSTRAINT FK_71CBFD049FF7D899');
        $this->addSql('ALTER TABLE administrative_document DROP CONSTRAINT FK_71CBFD04EDAE188E');
        $this->addSql('DROP TABLE administrative_document');
        $this->addSql('ALTER TABLE administrative_document DROP alert_number');
        $this->addSql('ALTER TABLE administrative_document DROP amount');
    }
}
