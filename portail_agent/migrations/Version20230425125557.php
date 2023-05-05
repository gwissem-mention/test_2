<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230425125557 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add victim_of_violence and victim_of_violence_text to facts';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE facts ADD victim_of_violence BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE facts ADD victim_of_violence_text TEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE facts DROP victim_of_violence');
        $this->addSql('ALTER TABLE facts DROP victim_of_violence_text');
    }
}
