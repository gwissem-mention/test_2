<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230613140310 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add family_situation field (Identity)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE identity ADD family_situation VARCHAR(255) DEFAULT NULL');
        $this->addSql('UPDATE identity SET family_situation = \'Célibataire\'');
        $this->addSql('ALTER TABLE identity ALTER family_situation SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE identity DROP family_situation');
    }
}
