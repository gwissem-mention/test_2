<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230210112908 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Rename facts_object table to multimedia_object';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE facts_object_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE multimedia_object_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE multimedia_object (id INT NOT NULL, complaint_id INT NOT NULL, identity_id INT DEFAULT NULL, label VARCHAR(255) NOT NULL, brand VARCHAR(255) DEFAULT NULL, model VARCHAR(255) DEFAULT NULL, operator VARCHAR(255) DEFAULT NULL, opposition BOOLEAN DEFAULT NULL, sim_number VARCHAR(255) DEFAULT NULL, amount DOUBLE PRECISION DEFAULT NULL, serial_number INT DEFAULT NULL, phone_number VARCHAR(255) DEFAULT NULL, belongs_to_the_victim BOOLEAN NOT NULL, thief_from_vehicule BOOLEAN NOT NULL, alert_number INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1FE49C1CEDAE188E ON multimedia_object (complaint_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1FE49C1CFF3ED4A8 ON multimedia_object (identity_id)');
        $this->addSql('ALTER TABLE multimedia_object ADD CONSTRAINT FK_1FE49C1CEDAE188E FOREIGN KEY (complaint_id) REFERENCES complaint (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE multimedia_object ADD CONSTRAINT FK_1FE49C1CFF3ED4A8 FOREIGN KEY (identity_id) REFERENCES identity (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE facts_object DROP CONSTRAINT fk_54526eb7edae188e');
        $this->addSql('ALTER TABLE facts_object DROP CONSTRAINT fk_54526eb7ff3ed4a8');
        $this->addSql('DROP TABLE facts_object');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE multimedia_object_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE facts_object_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE facts_object (id INT NOT NULL, complaint_id INT NOT NULL, identity_id INT DEFAULT NULL, label VARCHAR(255) NOT NULL, brand VARCHAR(255) DEFAULT NULL, model VARCHAR(255) DEFAULT NULL, operator VARCHAR(255) DEFAULT NULL, amount DOUBLE PRECISION DEFAULT NULL, imei INT DEFAULT NULL, phone_number VARCHAR(255) DEFAULT NULL, alert_number INT DEFAULT NULL, category VARCHAR(255) NOT NULL, opposition BOOLEAN DEFAULT NULL, sim_number VARCHAR(255) DEFAULT NULL, belongs_to_the_victim BOOLEAN NOT NULL, thief_from_vehicule BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_54526eb7ff3ed4a8 ON facts_object (identity_id)');
        $this->addSql('CREATE INDEX idx_54526eb7edae188e ON facts_object (complaint_id)');
        $this->addSql('ALTER TABLE facts_object ADD CONSTRAINT fk_54526eb7edae188e FOREIGN KEY (complaint_id) REFERENCES complaint (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE facts_object ADD CONSTRAINT fk_54526eb7ff3ed4a8 FOREIGN KEY (identity_id) REFERENCES identity (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE multimedia_object DROP CONSTRAINT FK_1FE49C1CEDAE188E');
        $this->addSql('ALTER TABLE multimedia_object DROP CONSTRAINT FK_1FE49C1CFF3ED4A8');
        $this->addSql('DROP TABLE multimedia_object');
    }
}
