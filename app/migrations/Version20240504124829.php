<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240504124829 extends AbstractMigration
{

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE log (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, payload VARCHAR(255) NOT NULL, service VARCHAR(255) NOT NULL, datetime DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , method VARCHAR(255) NOT NULL, resource VARCHAR(255) NOT NULL, protocol VARCHAR(255) NOT NULL, status_code INTEGER NOT NULL)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE log');
    }
}
