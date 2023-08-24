<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230823081342 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add check numbers to payment method';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE payment_method ADD cheque_number VARCHAR(20) DEFAULT NULL');
        $this->addSql('ALTER TABLE payment_method ADD first_cheque_number VARCHAR(20) DEFAULT NULL');
        $this->addSql('ALTER TABLE payment_method ADD last_cheque_number VARCHAR(20) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE payment_method DROP cheque_number');
        $this->addSql('ALTER TABLE payment_method DROP first_cheque_number');
        $this->addSql('ALTER TABLE payment_method DROP last_cheque_number');
    }
}
