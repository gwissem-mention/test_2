<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230106085634 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add alert_number facts_object and additional_information tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE additional_information ADD alert_number INT DEFAULT NULL');
        $this->addSql('ALTER TABLE facts_object ADD alert_number INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE additional_information DROP alert_number');
        $this->addSql('ALTER TABLE facts_object DROP alert_number');
    }
}
