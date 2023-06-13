<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230613081144 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Change appointment_contact_information column type to TEXT';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE complaint ALTER appointment_contact_information TYPE TEXT');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE complaint ALTER appointment_contact_information TYPE VARCHAR(255)');
    }
}
