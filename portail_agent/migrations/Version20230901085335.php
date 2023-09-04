<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230901085335 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE right_delegation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE right_delegation (id INT NOT NULL, delegating_agent_id INT NOT NULL, start_date DATE NOT NULL, end_date DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3B7E1B0B34277E17 ON right_delegation (delegating_agent_id)');
        $this->addSql('COMMENT ON COLUMN right_delegation.start_date IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN right_delegation.end_date IS \'(DC2Type:date_immutable)\'');
        $this->addSql('ALTER TABLE right_delegation ADD CONSTRAINT FK_3B7E1B0B34277E17 FOREIGN KEY (delegating_agent_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE right_delegation_id_seq CASCADE');
        $this->addSql('ALTER TABLE right_delegation DROP CONSTRAINT FK_3B7E1B0B34277E17');
        $this->addSql('DROP TABLE right_delegation');
    }
}
