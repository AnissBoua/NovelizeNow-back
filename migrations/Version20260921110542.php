<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260921110542 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chapter CHANGE status status ENUM(\'published\', \'in_progress\')');
        $this->addSql('ALTER TABLE comment ADD chapter_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C579F4768 FOREIGN KEY (chapter_id) REFERENCES chapter (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_9474526C579F4768 ON comment (chapter_id)');
        $this->addSql('ALTER TABLE novel CHANGE status status ENUM(\'published\', \'unpublished\')');
        $this->addSql('ALTER TABLE novel_image CHANGE img_position img_position ENUM(\'cover\', \'banner\')');
        $this->addSql('ALTER TABLE transaction CHANGE status status ENUM("pending", "completed", "canceled")');
        $this->addSql('ALTER TABLE user_novel CHANGE relation relation ENUM(\'author\')');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE novel CHANGE status status VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user_novel CHANGE relation relation VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE chapter CHANGE status status VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE transaction CHANGE status status VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C579F4768');
        $this->addSql('DROP INDEX IDX_9474526C579F4768 ON comment');
        $this->addSql('ALTER TABLE comment DROP chapter_id');
        $this->addSql('ALTER TABLE novel_image CHANGE img_position img_position VARCHAR(255) DEFAULT NULL');
    }
}
