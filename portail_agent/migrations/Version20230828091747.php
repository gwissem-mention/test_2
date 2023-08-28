<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230828091747 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Make description nullable in administrative_document';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE administrative_document ALTER description DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE administrative_document ALTER description SET NOT NULL');
    }
}
