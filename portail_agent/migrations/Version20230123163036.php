<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230123163036 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add user timezone';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "user" ADD timezone VARCHAR(255) DEFAULT \'Europe/Paris\' NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" DROP timezone');
    }
}
