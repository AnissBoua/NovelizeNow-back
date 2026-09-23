<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260923122021 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chapter ADD publish_at DATETIME DEFAULT NULL, CHANGE status status ENUM(\'published\', \'in_progress\', \'scheduled\')');
        $this->addSql('CREATE INDEX chapter_status_publish_at ON chapter (status, publish_at)');
        $this->addSql('ALTER TABLE novel ADD progress VARCHAR(20) DEFAULT \'ongoing\' NOT NULL, ADD rhythm VARCHAR(20) DEFAULT NULL, ADD release_day SMALLINT DEFAULT NULL, CHANGE status status ENUM(\'published\', \'unpublished\')');
        $this->addSql('ALTER TABLE novel_image CHANGE img_position img_position ENUM(\'cover\', \'banner\')');
        $this->addSql('ALTER TABLE transaction CHANGE status status ENUM("pending", "completed", "canceled")');
        $this->addSql('ALTER TABLE user_novel CHANGE relation relation ENUM(\'author\')');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX chapter_status_publish_at ON chapter');
        $this->addSql('ALTER TABLE chapter DROP publish_at, CHANGE status status VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE novel DROP progress, DROP rhythm, DROP release_day, CHANGE status status VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE novel_image CHANGE img_position img_position VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE transaction CHANGE status status VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user_novel CHANGE relation relation VARCHAR(255) DEFAULT NULL');
    }
}
