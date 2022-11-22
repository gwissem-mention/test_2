<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20221114134646 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add user table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, sub VARCHAR(255) NOT NULL, family_name VARCHAR(255) NOT NULL, given_name VARCHAR(255) NOT NULL, preferred_username VARCHAR(255) NOT NULL, birth_date DATE NOT NULL, birth_place VARCHAR(5) NOT NULL, birth_country VARCHAR(5) NOT NULL, gender VARCHAR(6) NOT NULL, email VARCHAR(255) NOT NULL, roles TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649580282DC ON "user" (sub)');
        $this->addSql('COMMENT ON COLUMN "user".birth_date IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "user".roles IS \'(DC2Type:simple_array)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('DROP TABLE "user"');
    }
}
