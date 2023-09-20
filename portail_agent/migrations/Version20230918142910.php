<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230918142910 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add calling_phone to facts';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE facts ADD calling_phone VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE facts DROP calling_phone');
    }
}
