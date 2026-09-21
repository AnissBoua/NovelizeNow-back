<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260914180342 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chapter DROP FOREIGN KEY FK_F981B52EB9E41394');
        $this->addSql('ALTER TABLE chapter CHANGE status status ENUM(\'published\', \'in_progress\')');
        $this->addSql('ALTER TABLE chapter ADD CONSTRAINT FK_F981B52EB9E41394 FOREIGN KEY (novel_id) REFERENCES novel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CB9E41394');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CF8697D13');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CB9E41394 FOREIGN KEY (novel_id) REFERENCES novel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CF8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B3B9E41394');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B3B9E41394 FOREIGN KEY (novel_id) REFERENCES novel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE novel CHANGE status status ENUM(\'published\', \'unpublished\')');
        $this->addSql('ALTER TABLE novel_image DROP FOREIGN KEY FK_E0AA285EB9E41394');
        $this->addSql('ALTER TABLE novel_image CHANGE img_position img_position ENUM(\'cover\', \'banner\')');
        $this->addSql('ALTER TABLE novel_image ADD CONSTRAINT FK_E0AA285EB9E41394 FOREIGN KEY (novel_id) REFERENCES novel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE page DROP FOREIGN KEY FK_140AB620579F4768');
        $this->addSql('ALTER TABLE page ADD CONSTRAINT FK_140AB620579F4768 FOREIGN KEY (chapter_id) REFERENCES chapter (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE transaction CHANGE status status ENUM("pending", "completed", "canceled")');
        $this->addSql('ALTER TABLE user_novel DROP FOREIGN KEY FK_6D55844FB9E41394');
        $this->addSql('ALTER TABLE user_novel CHANGE relation relation ENUM(\'author\')');
        $this->addSql('ALTER TABLE user_novel ADD CONSTRAINT FK_6D55844FB9E41394 FOREIGN KEY (novel_id) REFERENCES novel (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE novel_image DROP FOREIGN KEY FK_E0AA285EB9E41394');
        $this->addSql('ALTER TABLE novel_image CHANGE img_position img_position VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE novel_image ADD CONSTRAINT FK_E0AA285EB9E41394 FOREIGN KEY (novel_id) REFERENCES novel (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE chapter DROP FOREIGN KEY FK_F981B52EB9E41394');
        $this->addSql('ALTER TABLE chapter CHANGE status status VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE chapter ADD CONSTRAINT FK_F981B52EB9E41394 FOREIGN KEY (novel_id) REFERENCES novel (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE user_novel DROP FOREIGN KEY FK_6D55844FB9E41394');
        $this->addSql('ALTER TABLE user_novel CHANGE relation relation VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user_novel ADD CONSTRAINT FK_6D55844FB9E41394 FOREIGN KEY (novel_id) REFERENCES novel (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B3B9E41394');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B3B9E41394 FOREIGN KEY (novel_id) REFERENCES novel (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE novel CHANGE status status VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CB9E41394');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CF8697D13');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CB9E41394 FOREIGN KEY (novel_id) REFERENCES novel (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CF8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE transaction CHANGE status status VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE page DROP FOREIGN KEY FK_140AB620579F4768');
        $this->addSql('ALTER TABLE page ADD CONSTRAINT FK_140AB620579F4768 FOREIGN KEY (chapter_id) REFERENCES chapter (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
