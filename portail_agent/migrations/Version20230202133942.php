<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230202133942 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add clicked_at field to notification table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE notification ADD clicked_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN notification.clicked_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE notification DROP clicked_at');
    }
}
