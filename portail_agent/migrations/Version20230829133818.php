<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230829133818 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Set natures column nullable';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE facts ALTER natures DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE facts ALTER natures SET NOT NULL');
    }
}
