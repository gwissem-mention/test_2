<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230929091905 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add same_address_as_declarant to corporation';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE corporation ADD same_address_as_declarant BOOLEAN DEFAULT false NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE corporation DROP same_address_as_declarant');
    }
}
