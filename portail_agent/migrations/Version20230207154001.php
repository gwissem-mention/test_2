<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230207154001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add additional fields to complaint';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE complaint ADD test BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE complaint ADD start TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE complaint ADD finish TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE complaint ADD declarant_ip VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE complaint ADD tc_home INT NOT NULL');
        $this->addSql('ALTER TABLE complaint ADD tc_facts INT NOT NULL');
        $this->addSql('ALTER TABLE complaint ADD unit_code_tc_facts INT NOT NULL');
        $this->addSql('ALTER TABLE complaint ADD claims_legal_action BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE complaint ADD contact_window VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE complaint ADD contact_period VARCHAR(255) NOT NULL');
        $this->addSql('COMMENT ON COLUMN complaint.start IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN complaint.finish IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE complaint DROP test');
        $this->addSql('ALTER TABLE complaint DROP start');
        $this->addSql('ALTER TABLE complaint DROP finish');
        $this->addSql('ALTER TABLE complaint DROP declarant_ip');
        $this->addSql('ALTER TABLE complaint DROP tc_home');
        $this->addSql('ALTER TABLE complaint DROP tc_facts');
        $this->addSql('ALTER TABLE complaint DROP unit_code_tc_facts');
        $this->addSql('ALTER TABLE complaint DROP claims_legal_action');
        $this->addSql('ALTER TABLE complaint DROP contact_window');
        $this->addSql('ALTER TABLE complaint DROP contact_period');
    }
}
