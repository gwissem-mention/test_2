<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230911092552 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'add bank_account_Number field (PaymentMethod)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE payment_method ADD bank_account_number VARCHAR(255)');
        $this->addSql("UPDATE payment_method SET bank_account_number='987654321'");
        $this->addSql('ALTER TABLE payment_method ALTER COLUMN bank_account_number SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE payment_method DROP bank_account_number');
    }
}
