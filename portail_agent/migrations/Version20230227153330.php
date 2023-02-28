<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230227153330 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Remove unused fields (Facts) and add missing field (PaymentMethod)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE facts DROP no_violence');
        $this->addSql('ALTER TABLE facts DROP violence_description');
        $this->addSql('ALTER TABLE payment_method ADD bank VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE facts ADD no_violence BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE facts ADD violence_description VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE payment_method DROP bank');
    }
}
