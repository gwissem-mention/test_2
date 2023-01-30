<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230120134443 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add question table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE question_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE question (id INT NOT NULL, question VARCHAR(255) NOT NULL, answer TEXT NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE question_id_seq CASCADE');
        $this->addSql('DROP TABLE question');
    }
}
