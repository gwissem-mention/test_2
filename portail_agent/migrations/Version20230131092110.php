<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230131092110 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add Unique constraint on Complaint declaration_number and add ComplaintCount table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5F2732B5B481EC82 ON complaint (declaration_number)');
        $this->addSql('CREATE SEQUENCE complaint_count_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE complaint_count (id INT NOT NULL, year VARCHAR(255) NOT NULL, count INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C4023FD4BB827337 ON complaint_count (year)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX UNIQ_5F2732B5B481EC82');
        $this->addSql('DROP SEQUENCE complaint_count_id_seq CASCADE');
        $this->addSql('DROP TABLE complaint_count');
        $this->addSql('DROP INDEX UNIQ_C4023FD4BB827337');
    }
}
