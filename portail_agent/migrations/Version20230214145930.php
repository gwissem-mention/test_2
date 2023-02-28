<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
final class Version20230214145930 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Reverse adding missing fields for XML file generation (PaymentMethod, Object)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE object DROP CONSTRAINT fk_a8adabec9ff7d899');
        $this->addSql('DROP INDEX uniq_a8adabec9ff7d899');
        $this->addSql('ALTER TABLE object DROP victim_identity_id');
        $this->addSql('ALTER TABLE object DROP belongs_to_the_victim');
        $this->addSql('ALTER TABLE object DROP thief_from_vehicle');
        $this->addSql('ALTER TABLE payment_method DROP currency');
        $this->addSql('ALTER TABLE payment_method DROP number');
        $this->addSql('ALTER TABLE payment_method DROP opposition');
        $this->addSql('ALTER TABLE payment_method DROP bank');
        $this->addSql('ALTER TABLE payment_method DROP card_type');
        $this->addSql('ALTER TABLE payment_method DROP cheque_number');
        $this->addSql('ALTER TABLE payment_method DROP first_cheque_number');
        $this->addSql('ALTER TABLE payment_method DROP last_cheque_number');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE payment_method ADD currency VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE payment_method ADD number VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE payment_method ADD opposition BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE payment_method ADD bank VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE payment_method ADD card_type VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE payment_method ADD cheque_number VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE payment_method ADD first_cheque_number VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE payment_method ADD last_cheque_number VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE object ADD victim_identity_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE object ADD belongs_to_the_victim BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE object ADD thief_from_vehicle BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE object ADD CONSTRAINT fk_a8adabec9ff7d899 FOREIGN KEY (victim_identity_id) REFERENCES identity (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_a8adabec9ff7d899 ON object (victim_identity_id)');
    }
}
