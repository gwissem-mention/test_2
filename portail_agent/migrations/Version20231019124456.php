<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231019124456 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Remove personnel_id from table user';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "user" DROP personnel_id');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "user" ADD personnel_id VARCHAR(255) DEFAULT NULL');
    }
}
