<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230208145113 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Update Facts entity additional fields';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE facts ADD description VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE facts ADD exact_place_unknown BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE facts ADD country VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE facts ADD department VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE facts ADD department_number INT NOT NULL');
        $this->addSql('ALTER TABLE facts ADD city VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE facts ADD postal_code VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE facts ADD insee_code VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE facts ADD no_violence BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE facts ADD violence_description VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE facts ADD no_orientation BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE facts ADD orientation VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE facts ADD physical_prejudice BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE facts ADD physical_prejudice_description VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE facts ADD other_prejudice BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE facts ADD other_prejudice_description VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE facts DROP description');
        $this->addSql('ALTER TABLE facts DROP exact_place_unknown');
        $this->addSql('ALTER TABLE facts DROP country');
        $this->addSql('ALTER TABLE facts DROP department');
        $this->addSql('ALTER TABLE facts DROP department_number');
        $this->addSql('ALTER TABLE facts DROP city');
        $this->addSql('ALTER TABLE facts DROP postal_code');
        $this->addSql('ALTER TABLE facts DROP insee_code');
        $this->addSql('ALTER TABLE facts DROP no_violence');
        $this->addSql('ALTER TABLE facts DROP violence_description');
        $this->addSql('ALTER TABLE facts DROP no_orientation');
        $this->addSql('ALTER TABLE facts DROP orientation');
        $this->addSql('ALTER TABLE facts DROP physical_prejudice');
        $this->addSql('ALTER TABLE facts DROP physical_prejudice_description');
        $this->addSql('ALTER TABLE facts DROP other_prejudice');
        $this->addSql('ALTER TABLE facts DROP other_prejudice_description');
    }
}
