<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230112134232 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add user table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, identifier VARCHAR(510) NOT NULL, number VARCHAR(255) NOT NULL, roles JSON NOT NULL, appellation VARCHAR(255) NOT NULL, institution VARCHAR(255) NOT NULL, service_code VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649772E836A ON "user" (identifier)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64996901F543A9F98E5 ON "user" (number, institution)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE user_id_seq CASCADE');
        $this->addSql('DROP TABLE "user"');
    }
}
