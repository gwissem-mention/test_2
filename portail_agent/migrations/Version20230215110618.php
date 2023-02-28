<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230215110618 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Reverse adding missing fields for XML file generation (Complaint, Facts, Identity)';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE complaint DROP start');
        $this->addSql('ALTER TABLE complaint DROP finish');
        $this->addSql('ALTER TABLE complaint DROP declarant_ip');
        $this->addSql('ALTER TABLE complaint DROP tc_home');
        $this->addSql('ALTER TABLE complaint DROP tc_facts');
        $this->addSql('ALTER TABLE complaint DROP unit_code_tc_facts');
        $this->addSql('ALTER TABLE complaint DROP claims_legal_action');
        $this->addSql('ALTER TABLE complaint DROP contact_window');
        $this->addSql('ALTER TABLE complaint DROP contact_period');
        $this->addSql('ALTER TABLE facts DROP no_orientation');
        $this->addSql('ALTER TABLE facts DROP orientation');
        $this->addSql('ALTER TABLE facts DROP physical_prejudice');
        $this->addSql('ALTER TABLE facts DROP physical_prejudice_description');
        $this->addSql('ALTER TABLE facts DROP other_prejudice');
        $this->addSql('ALTER TABLE facts DROP other_prejudice_description');
        $this->addSql('ALTER TABLE identity DROP home_phone');
        $this->addSql('ALTER TABLE identity DROP office_phone');
        $this->addSql('ALTER TABLE identity DROP relationship_with_victime');
        $this->addSql('ALTER TABLE identity DROP family_status');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
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
        $this->addSql('ALTER TABLE identity ADD home_phone VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE identity ADD office_phone VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE identity ADD relationship_with_victime VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE identity ADD family_status VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE facts ADD no_orientation BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE facts ADD orientation VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE facts ADD physical_prejudice BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE facts ADD physical_prejudice_description VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE facts ADD other_prejudice BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE facts ADD other_prejudice_description VARCHAR(255) NOT NULL');
    }
}
