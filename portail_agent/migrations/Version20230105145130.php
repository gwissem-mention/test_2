<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230105145130 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add end_adresse, add address_additional_information';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE facts ADD end_address VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE facts ADD address_additional_information TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE facts RENAME COLUMN address TO start_address');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE facts DROP end_address');
        $this->addSql('ALTER TABLE facts DROP address_additional_information');
        $this->addSql('ALTER TABLE facts RENAME COLUMN start_address TO address');
    }
}
