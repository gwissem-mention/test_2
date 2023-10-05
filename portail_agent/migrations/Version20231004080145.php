<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231004080145 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add upload_report table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE upload_report_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE upload_report (id INT NOT NULL, complaint_id INT NOT NULL, oodrive_id VARCHAR(255) NOT NULL, timestamp INT NOT NULL, size INT NOT NULL, type VARCHAR(255) NOT NULL, origin_name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_490E273CEDAE188E ON upload_report (complaint_id)');
        $this->addSql('ALTER TABLE upload_report ADD CONSTRAINT FK_490E273CEDAE188E FOREIGN KEY (complaint_id) REFERENCES complaint (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE upload_report_id_seq CASCADE');
        $this->addSql('ALTER TABLE upload_report DROP CONSTRAINT FK_490E273CEDAE188E');
        $this->addSql('DROP TABLE upload_report');
    }
}
