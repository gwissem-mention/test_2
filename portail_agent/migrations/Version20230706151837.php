<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230706151837 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add witness table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE witness_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE witness (id INT NOT NULL, additional_information_id INT NOT NULL, description TEXT DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CFF1AA0E69818E1C ON witness (additional_information_id)');
        $this->addSql('ALTER TABLE witness ADD CONSTRAINT FK_CFF1AA0E69818E1C FOREIGN KEY (additional_information_id) REFERENCES additional_information (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE additional_information DROP witnesses_present_text');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE witness_id_seq CASCADE');
        $this->addSql('ALTER TABLE witness DROP CONSTRAINT FK_CFF1AA0E69818E1C');
        $this->addSql('DROP TABLE witness');
        $this->addSql('ALTER TABLE additional_information ADD witnesses_present_text TEXT DEFAULT NULL');
    }
}
