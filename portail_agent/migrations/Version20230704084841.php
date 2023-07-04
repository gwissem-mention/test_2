<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230704084841 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add mobile object fields';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE multimedia_object ADD still_on_when_mobile_stolen BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE multimedia_object ADD keyboard_locked_when_mobile_stolen BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE multimedia_object ADD pin_enabled_when_mobile_stolen BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE multimedia_object ADD mobile_insured BOOLEAN DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE multimedia_object DROP still_on_when_mobile_stolen');
        $this->addSql('ALTER TABLE multimedia_object DROP keyboard_locked_when_mobile_stolen');
        $this->addSql('ALTER TABLE multimedia_object DROP pin_enabled_when_mobile_stolen');
        $this->addSql('ALTER TABLE multimedia_object DROP mobile_insured');
    }
}
