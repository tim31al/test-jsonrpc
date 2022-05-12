<?php

declare(strict_types=1);

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220512063127 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE activity_clicks_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE activity_clicks (id INT NOT NULL, url VARCHAR(255) NOT NULL, counter INT DEFAULT 0 NOT NULL, last_visit TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_72ACF901F47645AE ON activity_clicks (url)');
        $this->addSql('CREATE INDEX activity_url_idx ON activity_clicks (url)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE activity_clicks_id_seq CASCADE');
        $this->addSql('DROP TABLE activity_clicks');
    }
}
