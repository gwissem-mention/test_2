<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230213111802 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Update administrative document and multimedia object with Doctrine inheritance';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE multimedia_object_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE administrative_document_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE object_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE object (id INT NOT NULL, victim_identity_id INT DEFAULT NULL, complaint_id INT NOT NULL, amount DOUBLE PRECISION DEFAULT NULL, belongs_to_the_victim BOOLEAN NOT NULL, thief_from_vehicle BOOLEAN NOT NULL, alert_number INT DEFAULT NULL, discr VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A8ADABEC9FF7D899 ON object (victim_identity_id)');
        $this->addSql('CREATE INDEX IDX_A8ADABECEDAE188E ON object (complaint_id)');
        $this->addSql('ALTER TABLE object ADD CONSTRAINT FK_A8ADABEC9FF7D899 FOREIGN KEY (victim_identity_id) REFERENCES identity (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE object ADD CONSTRAINT FK_A8ADABECEDAE188E FOREIGN KEY (complaint_id) REFERENCES complaint (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE administrative_document DROP CONSTRAINT fk_71cbfd049ff7d899');
        $this->addSql('ALTER TABLE administrative_document DROP CONSTRAINT fk_71cbfd04edae188e');
        $this->addSql('DROP INDEX idx_71cbfd04edae188e');
        $this->addSql('DROP INDEX uniq_71cbfd049ff7d899');
        $this->addSql('ALTER TABLE administrative_document DROP victim_identity_id');
        $this->addSql('ALTER TABLE administrative_document DROP complaint_id');
        $this->addSql('ALTER TABLE administrative_document DROP belongs_to_the_victim');
        $this->addSql('ALTER TABLE administrative_document DROP thief_from_vehicule');
        $this->addSql('ALTER TABLE administrative_document DROP alert_number');
        $this->addSql('ALTER TABLE administrative_document DROP amount');
        $this->addSql('ALTER TABLE administrative_document ADD CONSTRAINT FK_71CBFD04BF396750 FOREIGN KEY (id) REFERENCES object (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE multimedia_object DROP CONSTRAINT fk_1fe49c1cedae188e');
        $this->addSql('ALTER TABLE multimedia_object DROP CONSTRAINT fk_1fe49c1cff3ed4a8');
        $this->addSql('DROP INDEX uniq_1fe49c1cff3ed4a8');
        $this->addSql('DROP INDEX idx_1fe49c1cedae188e');
        $this->addSql('ALTER TABLE multimedia_object DROP complaint_id');
        $this->addSql('ALTER TABLE multimedia_object DROP identity_id');
        $this->addSql('ALTER TABLE multimedia_object DROP amount');
        $this->addSql('ALTER TABLE multimedia_object DROP belongs_to_the_victim');
        $this->addSql('ALTER TABLE multimedia_object DROP thief_from_vehicule');
        $this->addSql('ALTER TABLE multimedia_object DROP alert_number');
        $this->addSql('ALTER TABLE multimedia_object ADD CONSTRAINT FK_1FE49C1CBF396750 FOREIGN KEY (id) REFERENCES object (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE administrative_document DROP CONSTRAINT FK_71CBFD04BF396750');
        $this->addSql('ALTER TABLE multimedia_object DROP CONSTRAINT FK_1FE49C1CBF396750');
        $this->addSql('DROP SEQUENCE object_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE multimedia_object_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE administrative_document_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('ALTER TABLE object DROP CONSTRAINT FK_A8ADABEC9FF7D899');
        $this->addSql('ALTER TABLE object DROP CONSTRAINT FK_A8ADABECEDAE188E');
        $this->addSql('DROP TABLE object');
        $this->addSql('ALTER TABLE administrative_document ADD victim_identity_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE administrative_document ADD complaint_id INT NOT NULL');
        $this->addSql('ALTER TABLE administrative_document ADD belongs_to_the_victim BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE administrative_document ADD thief_from_vehicule BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE administrative_document ADD alert_number INT DEFAULT NULL');
        $this->addSql('ALTER TABLE administrative_document ADD amount DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE administrative_document ADD CONSTRAINT fk_71cbfd049ff7d899 FOREIGN KEY (victim_identity_id) REFERENCES identity (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE administrative_document ADD CONSTRAINT fk_71cbfd04edae188e FOREIGN KEY (complaint_id) REFERENCES complaint (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_71cbfd04edae188e ON administrative_document (complaint_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_71cbfd049ff7d899 ON administrative_document (victim_identity_id)');
        $this->addSql('ALTER TABLE multimedia_object ADD complaint_id INT NOT NULL');
        $this->addSql('ALTER TABLE multimedia_object ADD identity_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE multimedia_object ADD amount DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE multimedia_object ADD belongs_to_the_victim BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE multimedia_object ADD thief_from_vehicule BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE multimedia_object ADD alert_number INT DEFAULT NULL');
        $this->addSql('ALTER TABLE multimedia_object ADD CONSTRAINT fk_1fe49c1cedae188e FOREIGN KEY (complaint_id) REFERENCES complaint (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE multimedia_object ADD CONSTRAINT fk_1fe49c1cff3ed4a8 FOREIGN KEY (identity_id) REFERENCES identity (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_1fe49c1cff3ed4a8 ON multimedia_object (identity_id)');
        $this->addSql('CREATE INDEX idx_1fe49c1cedae188e ON multimedia_object (complaint_id)');
    }
}
