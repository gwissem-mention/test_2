<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230822115800 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add job_thesaurus field (Identity)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE identity ADD job_thesaurus VARCHAR(255)');
        $this->addSql('UPDATE identity SET job_thesaurus=\'FONCTIONNAIRE\'');
        $this->addSql('ALTER TABLE identity ALTER COLUMN job_thesaurus SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE identity DROP job_thesaurus');
    }
}
