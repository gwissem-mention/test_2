<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230817145936 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add description to administrative object';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE administrative_document ADD description VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE multimedia_object ALTER owned DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE administrative_document DROP description');
        $this->addSql('ALTER TABLE multimedia_object ALTER owned SET DEFAULT true');
    }
}
