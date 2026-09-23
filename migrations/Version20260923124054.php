<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260923124054 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chapter CHANGE status status ENUM(\'published\', \'in_progress\', \'scheduled\')');
        $this->addSql('ALTER TABLE novel ADD published_at DATETIME DEFAULT NULL');
        // No historical publish date exists, so the creation date is the best available value.
        $this->addSql('UPDATE novel SET published_at = date_creation WHERE status = \'published\'');
        $this->addSql('ALTER TABLE novel DROP status');
        $this->addSql('CREATE INDEX novel_published_at ON novel (published_at)');
        $this->addSql('ALTER TABLE novel_image CHANGE img_position img_position ENUM(\'cover\', \'banner\')');
        $this->addSql('ALTER TABLE transaction CHANGE status status ENUM("pending", "completed", "canceled")');
        $this->addSql('ALTER TABLE user_novel CHANGE relation relation ENUM(\'author\')');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chapter CHANGE status status VARCHAR(255) DEFAULT NULL');
        $this->addSql('DROP INDEX novel_published_at ON novel');
        $this->addSql('ALTER TABLE novel ADD status ENUM(\'published\', \'unpublished\')');
        $this->addSql('UPDATE novel SET status = IF(published_at IS NULL, \'unpublished\', \'published\')');
        $this->addSql('ALTER TABLE novel DROP published_at');
        $this->addSql('ALTER TABLE novel_image CHANGE img_position img_position VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE transaction CHANGE status status VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user_novel CHANGE relation relation VARCHAR(255) DEFAULT NULL');
    }
}
