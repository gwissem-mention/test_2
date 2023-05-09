<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230428122336 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Drop victim_of_violence and victim_of_violence_text from additional_information';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE additional_information DROP victim_of_violence');
        $this->addSql('ALTER TABLE additional_information DROP victim_of_violence_text');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE additional_information ADD victim_of_violence BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE additional_information ADD victim_of_violence_text TEXT DEFAULT NULL');
    }
}
