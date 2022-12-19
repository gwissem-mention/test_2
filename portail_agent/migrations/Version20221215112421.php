<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20221215112421 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add alerts fields';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE facts ADD alerts INT DEFAULT NULL');
        $this->addSql('ALTER TABLE identity ADD alerts INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE facts DROP alerts');
        $this->addSql('ALTER TABLE identity DROP alerts');
    }
}
