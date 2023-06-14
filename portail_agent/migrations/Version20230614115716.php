<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230614115716 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'update code reject-reason type';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE complaint ALTER refusal_reason TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE reject_reason ALTER code TYPE VARCHAR(255)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE complaint ALTER refusal_reason TYPE INT');
        $this->addSql('ALTER TABLE reject_reason ALTER code TYPE INT');
    }
}
