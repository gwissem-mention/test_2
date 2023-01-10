<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230105150844 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Update date / hour fields';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE facts ADD exact_date_known BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE facts ADD end_date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE facts ADD exact_hour_known INT NOT NULL');
        $this->addSql('ALTER TABLE facts RENAME COLUMN date TO start_date');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE facts DROP exact_date_known');
        $this->addSql('ALTER TABLE facts DROP end_date');
        $this->addSql('ALTER TABLE facts DROP exact_hour_known');
        $this->addSql('ALTER TABLE facts RENAME COLUMN start_date TO date');
    }
}
