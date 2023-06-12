<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230606152130 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add home_phone field and remove mobile_phone not null (Identity)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE identity ADD home_phone VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE identity ALTER mobile_phone DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE identity DROP home_phone');
        $this->addSql('ALTER TABLE identity ALTER mobile_phone SET NOT NULL');
    }
}
