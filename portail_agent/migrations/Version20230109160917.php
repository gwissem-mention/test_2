<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230109160917 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add author and published_at field to comment table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE comment ADD author VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE comment ADD published_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('COMMENT ON COLUMN comment.published_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE comment DROP author');
        $this->addSql('ALTER TABLE comment DROP published_at');
    }
}
