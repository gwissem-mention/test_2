<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230608140617 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add reject reason table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE reject_reason_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE reject_reason (id INT NOT NULL, label VARCHAR(255) NOT NULL, code INT NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE reject_reason_id_seq CASCADE');
        $this->addSql('DROP TABLE reject_reason');
    }
}
