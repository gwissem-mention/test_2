<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230213145837 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add payment_method table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE payment_method (id INT NOT NULL, type VARCHAR(255) NOT NULL, currency VARCHAR(255) NOT NULL, number VARCHAR(255) DEFAULT NULL, description VARCHAR(255) NOT NULL, opposition BOOLEAN DEFAULT NULL, bank VARCHAR(255) DEFAULT NULL, card_type VARCHAR(255) DEFAULT NULL, cheque_number VARCHAR(255) DEFAULT NULL, first_cheque_number VARCHAR(255) DEFAULT NULL, last_cheque_number VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE payment_method ADD CONSTRAINT FK_7B61A1F6BF396750 FOREIGN KEY (id) REFERENCES object (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE payment_method DROP CONSTRAINT FK_7B61A1F6BF396750');
        $this->addSql('DROP TABLE payment_method');
    }
}
