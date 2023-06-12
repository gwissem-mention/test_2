<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230608145210 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Update fields specifications (AdministrativeDocument, Complaint, Corporation, Identity)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE administrative_document ALTER owner_email TYPE VARCHAR(254)');
        $this->addSql('ALTER TABLE complaint ALTER refusal_text TYPE VARCHAR(3000)');
        $this->addSql('ALTER TABLE corporation ALTER contact_email TYPE VARCHAR(254)');
        $this->addSql('ALTER TABLE identity ALTER email TYPE VARCHAR(254)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE corporation ALTER contact_email TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE administrative_document ALTER owner_email TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE complaint ALTER refusal_text TYPE TEXT');
        $this->addSql('ALTER TABLE complaint ALTER refusal_text TYPE TEXT');
        $this->addSql('ALTER TABLE identity ALTER email TYPE VARCHAR(255)');
    }
}
