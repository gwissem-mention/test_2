<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231031112058 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Update delegating_agent to ManyToOne relation (RightDelegation)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP INDEX uniq_3b7e1b0b34277e17');
        $this->addSql('CREATE INDEX IDX_3B7E1B0B34277E17 ON right_delegation (delegating_agent_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX IDX_3B7E1B0B34277E17');
        $this->addSql('CREATE UNIQUE INDEX uniq_3b7e1b0b34277e17 ON right_delegation (delegating_agent_id)');
    }
}
