<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230913114132 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add On delete cascade for delegationGained (User)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D64966E5A61B');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D64966E5A61B FOREIGN KEY (delegation_gained_id) REFERENCES right_delegation (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT fk_8d93d64966e5a61b');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT fk_8d93d64966e5a61b FOREIGN KEY (delegation_gained_id) REFERENCES right_delegation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
