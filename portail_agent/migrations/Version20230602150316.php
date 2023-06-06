<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230602150316 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add status to object and remove default values';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE administrative_document ALTER owned DROP DEFAULT');
        $this->addSql('ALTER TABLE complaint ALTER france_connected DROP DEFAULT');
        $this->addSql('ALTER TABLE complaint ALTER unit_reassignment_asked DROP DEFAULT');
        $this->addSql('ALTER TABLE object ADD status INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE object DROP status');
        $this->addSql('ALTER TABLE administrative_document ALTER owned SET DEFAULT true');
        $this->addSql('ALTER TABLE complaint ALTER unit_reassignment_asked SET DEFAULT false');
        $this->addSql('ALTER TABLE complaint ALTER france_connected SET DEFAULT false');
    }
}
