<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230718072408 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add reassignment_counter field';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE complaint ADD reassignment_counter INT DEFAULT 0 NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE complaint DROP reassignment_counter');
    }
}
