<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230316134954 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add person legal represented (Complaint)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE complaint ADD person_legal_represented_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE complaint ADD CONSTRAINT FK_5F2732B53FEDDD8C FOREIGN KEY (person_legal_represented_id) REFERENCES identity (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5F2732B53FEDDD8C ON complaint (person_legal_represented_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE complaint DROP CONSTRAINT FK_5F2732B53FEDDD8C');
        $this->addSql('DROP INDEX UNIQ_5F2732B53FEDDD8C');
        $this->addSql('ALTER TABLE complaint DROP person_legal_represented_id');
    }
}
