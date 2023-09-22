<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230922091702 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Rename start_address_* fields and add end_address_* fields (Facts)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE facts RENAME COLUMN country TO start_address_country');
        $this->addSql('ALTER TABLE facts RENAME COLUMN department TO start_address_department');
        $this->addSql('ALTER TABLE facts RENAME COLUMN city TO start_address_city');
        $this->addSql('ALTER TABLE facts RENAME COLUMN postal_code TO start_address_postal_code');
        $this->addSql('ALTER TABLE facts RENAME COLUMN insee_code TO start_address_insee_code');
        $this->addSql('ALTER TABLE facts RENAME COLUMN department_number TO start_address_department_number');
        $this->addSql('ALTER TABLE facts ADD end_address_country VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE facts ADD end_address_department VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE facts ADD end_address_department_number INT DEFAULT NULL');
        $this->addSql('ALTER TABLE facts ADD end_address_city VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE facts ADD end_address_postal_code VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE facts ADD end_address_insee_code VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE facts RENAME COLUMN start_address_country TO country');
        $this->addSql('ALTER TABLE facts RENAME COLUMN start_address_department TO department');
        $this->addSql('ALTER TABLE facts RENAME COLUMN start_address_city TO city');
        $this->addSql('ALTER TABLE facts RENAME COLUMN start_address_postal_code TO postal_code');
        $this->addSql('ALTER TABLE facts RENAME COLUMN start_address_insee_code TO insee_code');
        $this->addSql('ALTER TABLE facts RENAME COLUMN start_address_department_number TO department_number');
        $this->addSql('ALTER TABLE facts DROP end_address_country');
        $this->addSql('ALTER TABLE facts DROP end_address_department');
        $this->addSql('ALTER TABLE facts DROP end_address_department_number');
        $this->addSql('ALTER TABLE facts DROP end_address_city');
        $this->addSql('ALTER TABLE facts DROP end_address_postal_code');
        $this->addSql('ALTER TABLE facts DROP end_address_insee_code');
    }
}
