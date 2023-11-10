<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231109100312 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add table users, transfer users data from user to users, then delete user table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE notification DROP CONSTRAINT fk_bf5476caa76ed395');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT fk_9474526cf675f31b');
        $this->addSql('ALTER TABLE right_delegation DROP CONSTRAINT fk_3b7e1b0b34277e17');
        $this->addSql('ALTER TABLE complaint DROP CONSTRAINT fk_5f2732b5f4bd7827');
        $this->addSql('DROP SEQUENCE user_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE "users_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "users" (id INT NOT NULL, delegation_gained_id INT DEFAULT NULL, identifier VARCHAR(510) NOT NULL, number VARCHAR(255) NOT NULL, roles JSON NOT NULL, appellation VARCHAR(255) NOT NULL, institution VARCHAR(255) NOT NULL, service_code VARCHAR(255) NOT NULL, timezone VARCHAR(255) DEFAULT \'Europe/Paris\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9772E836A ON "users" (identifier)');
        $this->addSql('CREATE INDEX IDX_1483A5E966E5A61B ON "users" (delegation_gained_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E996901F543A9F98E5 ON "users" (number, institution)');
        $this->addSql('ALTER TABLE "users" ADD CONSTRAINT FK_1483A5E966E5A61B FOREIGN KEY (delegation_gained_id) REFERENCES right_delegation (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('INSERT INTO "users" (id, delegation_gained_id, identifier, number, roles, appellation, institution, service_code, timezone)
            SELECT id, delegation_gained_id, identifier, number, roles, appellation, institution, service_code, timezone
            FROM "user"');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT fk_8d93d64966e5a61b');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CF675F31B FOREIGN KEY (author_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE complaint ADD CONSTRAINT FK_5F2732B5F4BD7827 FOREIGN KEY (assigned_to_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAA76ED395 FOREIGN KEY (user_id) REFERENCES "users" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE right_delegation ADD CONSTRAINT FK_3B7E1B0B34277E17 FOREIGN KEY (delegating_agent_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526CF675F31B');
        $this->addSql('ALTER TABLE complaint DROP CONSTRAINT FK_5F2732B5F4BD7827');
        $this->addSql('ALTER TABLE notification DROP CONSTRAINT FK_BF5476CAA76ED395');
        $this->addSql('ALTER TABLE right_delegation DROP CONSTRAINT FK_3B7E1B0B34277E17');
        $this->addSql('DROP SEQUENCE "users_id_seq" CASCADE');
        $this->addSql('CREATE SEQUENCE user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, delegation_gained_id INT DEFAULT NULL, identifier VARCHAR(510) NOT NULL, number VARCHAR(255) NOT NULL, roles JSON NOT NULL, appellation VARCHAR(255) NOT NULL, institution VARCHAR(255) NOT NULL, service_code VARCHAR(255) NOT NULL, timezone VARCHAR(255) DEFAULT \'Europe/Paris\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_8d93d64966e5a61b ON "user" (delegation_gained_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_8d93d64996901f543a9f98e5 ON "user" (number, institution)');
        $this->addSql('CREATE UNIQUE INDEX uniq_8d93d649772e836a ON "user" (identifier)');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT fk_8d93d64966e5a61b FOREIGN KEY (delegation_gained_id) REFERENCES right_delegation (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "users" DROP CONSTRAINT FK_1483A5E966E5A61B');
        $this->addSql('DROP TABLE "users"');
        $this->addSql('ALTER TABLE notification DROP CONSTRAINT fk_bf5476caa76ed395');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT fk_bf5476caa76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT fk_9474526cf675f31b');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT fk_9474526cf675f31b FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE right_delegation DROP CONSTRAINT fk_3b7e1b0b34277e17');
        $this->addSql('ALTER TABLE right_delegation ADD CONSTRAINT fk_3b7e1b0b34277e17 FOREIGN KEY (delegating_agent_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE complaint DROP CONSTRAINT fk_5f2732b5f4bd7827');
        $this->addSql('ALTER TABLE complaint ADD CONSTRAINT fk_5f2732b5f4bd7827 FOREIGN KEY (assigned_to_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
