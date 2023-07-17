<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230707081312 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Rename SirenNumber to SiretNumber and reduce its size to 14 characters';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE corporation RENAME COLUMN siren_number TO siret_number');
        $this->addSql('ALTER TABLE corporation ALTER siret_number TYPE VARCHAR(14)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE corporation RENAME COLUMN siret_number TO siren_number');
        $this->addSql('ALTER TABLE corporation ALTER siret_number TYPE VARCHAR(255)');
    }
}
