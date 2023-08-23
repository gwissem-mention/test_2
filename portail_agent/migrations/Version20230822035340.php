<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230822035340 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Delete the Denomination field';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE multimedia_object DROP label');
        $this->addSql('ALTER TABLE multimedia_object ALTER owned DROP DEFAULT');
        $this->addSql('ALTER TABLE vehicle DROP label');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE multimedia_object ADD label VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE multimedia_object ALTER owned SET DEFAULT true');
        $this->addSql('ALTER TABLE vehicle ADD label VARCHAR(255) NOT NULL');
    }
}
