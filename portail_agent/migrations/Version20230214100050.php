<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230214100050 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add simple object table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE simple_object (id INT NOT NULL, nature VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE simple_object ADD CONSTRAINT FK_3E161188BF396750 FOREIGN KEY (id) REFERENCES object (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE simple_object DROP CONSTRAINT FK_3E161188BF396750');
        $this->addSql('DROP TABLE simple_object');
    }
}
