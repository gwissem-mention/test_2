<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230921094642 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add credit card number to payment method';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE payment_method ADD credit_card_number VARCHAR(30) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE payment_method DROP credit_card_number');
    }
}
