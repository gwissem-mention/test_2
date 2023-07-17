<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230712144105 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add consent_confirmation field';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE complaint ADD consent_contact_electronics BOOLEAN');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE complaint DROP consent_contact_electronics');
    }
}
