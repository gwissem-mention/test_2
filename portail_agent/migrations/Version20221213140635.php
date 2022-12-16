<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20221213140635 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Complaint tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE additional_information_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE complaint_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE facts_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE facts_object_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE identity_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE additional_information (id INT NOT NULL, cctv_present INT NOT NULL, cctv_available BOOLEAN DEFAULT NULL, suspects_known BOOLEAN NOT NULL, suspects_known_text TEXT DEFAULT NULL, witnesses_present BOOLEAN NOT NULL, witnesses_present_text TEXT DEFAULT NULL, victim_of_violence BOOLEAN DEFAULT NULL, victim_of_violence_text TEXT DEFAULT NULL, fsi_visit BOOLEAN NOT NULL, observation_made BOOLEAN DEFAULT NULL, description TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE complaint (id INT NOT NULL, identity_id INT NOT NULL, facts_id INT NOT NULL, additional_information_id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, appointment_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, status VARCHAR(255) NOT NULL, declaration_number VARCHAR(255) NOT NULL, assigned_agent VARCHAR(255) NOT NULL, optin_notification BOOLEAN NOT NULL, comments_number INT NOT NULL, alert VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5F2732B5FF3ED4A8 ON complaint (identity_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5F2732B53CDF19AF ON complaint (facts_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5F2732B569818E1C ON complaint (additional_information_id)');
        $this->addSql('COMMENT ON COLUMN complaint.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN complaint.appointment_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE facts (id INT NOT NULL, nature INT NOT NULL, place VARCHAR(255) NOT NULL, date DATE NOT NULL, address VARCHAR(255) NOT NULL, start_hour TIME(0) WITHOUT TIME ZONE DEFAULT NULL, end_hour TIME(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE facts_object (id INT NOT NULL, complaint_id INT NOT NULL, label VARCHAR(255) NOT NULL, brand VARCHAR(255) DEFAULT NULL, model VARCHAR(255) DEFAULT NULL, operator VARCHAR(255) DEFAULT NULL, amount DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_54526EB7EDAE188E ON facts_object (complaint_id)');
        $this->addSql('CREATE TABLE identity (id INT NOT NULL, declarant_status INT NOT NULL, civility INT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, birthday DATE NOT NULL, birth_country VARCHAR(255) NOT NULL, birth_department VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, address_city VARCHAR(255) NOT NULL, address_postcode VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, nationality VARCHAR(255) NOT NULL, job VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE complaint ADD CONSTRAINT FK_5F2732B5FF3ED4A8 FOREIGN KEY (identity_id) REFERENCES identity (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE complaint ADD CONSTRAINT FK_5F2732B53CDF19AF FOREIGN KEY (facts_id) REFERENCES facts (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE complaint ADD CONSTRAINT FK_5F2732B569818E1C FOREIGN KEY (additional_information_id) REFERENCES additional_information (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE facts_object ADD CONSTRAINT FK_54526EB7EDAE188E FOREIGN KEY (complaint_id) REFERENCES complaint (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE additional_information_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE complaint_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE facts_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE facts_object_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE identity_id_seq CASCADE');
        $this->addSql('ALTER TABLE complaint DROP CONSTRAINT FK_5F2732B5FF3ED4A8');
        $this->addSql('ALTER TABLE complaint DROP CONSTRAINT FK_5F2732B53CDF19AF');
        $this->addSql('ALTER TABLE complaint DROP CONSTRAINT FK_5F2732B569818E1C');
        $this->addSql('ALTER TABLE facts_object DROP CONSTRAINT FK_54526EB7EDAE188E');
        $this->addSql('DROP TABLE additional_information');
        $this->addSql('DROP TABLE complaint');
        $this->addSql('DROP TABLE facts');
        $this->addSql('DROP TABLE facts_object');
        $this->addSql('DROP TABLE identity');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
