<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231117133651 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add attachment_download table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE attachment_download_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE attachment_download (id INT NOT NULL, complaint_id INT NOT NULL, downloaded_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, cleaned_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7C093130EDAE188E ON attachment_download (complaint_id)');
        $this->addSql('COMMENT ON COLUMN attachment_download.downloaded_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN attachment_download.cleaned_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE attachment_download ADD CONSTRAINT FK_7C093130EDAE188E FOREIGN KEY (complaint_id) REFERENCES complaint (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE attachment_download_id_seq CASCADE');
        $this->addSql('ALTER TABLE attachment_download DROP CONSTRAINT FK_7C093130EDAE188E');
        $this->addSql('DROP TABLE attachment_download');
    }
}
