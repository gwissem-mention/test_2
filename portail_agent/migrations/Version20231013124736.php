<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231013124736 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Make unit_assigned nullable';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE complaint ALTER unit_assigned DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE complaint ALTER unit_assigned SET NOT NULL');
    }
}
