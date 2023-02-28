<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230208161234 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Update FactsObject entity';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE facts_object ADD identity_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE facts_object ADD category VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE facts_object ADD opposition BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE facts_object ADD sim_number VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE facts_object ADD belongs_to_the_victim BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE facts_object ADD thief_from_vehicule BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE facts_object ADD CONSTRAINT FK_54526EB7FF3ED4A8 FOREIGN KEY (identity_id) REFERENCES identity (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_54526EB7FF3ED4A8 ON facts_object (identity_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE facts_object DROP CONSTRAINT FK_54526EB7FF3ED4A8');
        $this->addSql('DROP INDEX UNIQ_54526EB7FF3ED4A8');
        $this->addSql('ALTER TABLE facts_object DROP identity_id');
        $this->addSql('ALTER TABLE facts_object DROP category');
        $this->addSql('ALTER TABLE facts_object DROP opposition');
        $this->addSql('ALTER TABLE facts_object DROP sim_number');
        $this->addSql('ALTER TABLE facts_object DROP belongs_to_the_victim');
        $this->addSql('ALTER TABLE facts_object DROP thief_from_vehicule');
    }
}
