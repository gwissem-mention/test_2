<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230105133641 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Update facts natures to array field';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE facts ADD natures TEXT NOT NULL');
        $this->addSql('ALTER TABLE facts DROP nature');
        $this->addSql('COMMENT ON COLUMN facts.natures IS \'(DC2Type:simple_array)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE facts ADD nature INT NOT NULL');
        $this->addSql('ALTER TABLE facts DROP natures');
    }
}
