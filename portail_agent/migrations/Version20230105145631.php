<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230105145631 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add a IMEI number and a phone number to the FactsObject entity';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE facts_object ADD imei INT DEFAULT NULL');
        $this->addSql('ALTER TABLE facts_object ADD phone_number VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE facts_object DROP imei');
        $this->addSql('ALTER TABLE facts_object DROP phone_number');
    }
}
