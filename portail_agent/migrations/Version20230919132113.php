<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230919132113 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add website to facts';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE facts ADD website VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE facts DROP website');
    }
}
