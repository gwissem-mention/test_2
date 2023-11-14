<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231113130124 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Drop exact_place_unknown from facts table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE facts DROP exact_place_unknown');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE facts ADD exact_place_unknown BOOLEAN NOT NULL');
    }
}
