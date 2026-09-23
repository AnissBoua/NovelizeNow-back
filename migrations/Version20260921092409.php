<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260921092409 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment_like (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, comment_id INT NOT NULL, INDEX IDX_8A55E25FA76ED395 (user_id), INDEX IDX_8A55E25FF8697D13 (comment_id), UNIQUE INDEX comment_like_unique (user_id, comment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE library_entry (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, novel_id INT NOT NULL, date_creation DATETIME NOT NULL, INDEX IDX_18844F5FA76ED395 (user_id), INDEX IDX_18844F5FB9E41394 (novel_id), UNIQUE INDEX library_entry_unique (user_id, novel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reading_progress (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, novel_id INT NOT NULL, chapter_id INT NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_74F6AFF6A76ED395 (user_id), INDEX IDX_74F6AFF6B9E41394 (novel_id), INDEX IDX_74F6AFF6579F4768 (chapter_id), UNIQUE INDEX reading_progress_unique (user_id, novel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment_like ADD CONSTRAINT FK_8A55E25FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment_like ADD CONSTRAINT FK_8A55E25FF8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE library_entry ADD CONSTRAINT FK_18844F5FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE library_entry ADD CONSTRAINT FK_18844F5FB9E41394 FOREIGN KEY (novel_id) REFERENCES novel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reading_progress ADD CONSTRAINT FK_74F6AFF6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reading_progress ADD CONSTRAINT FK_74F6AFF6B9E41394 FOREIGN KEY (novel_id) REFERENCES novel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reading_progress ADD CONSTRAINT FK_74F6AFF6579F4768 FOREIGN KEY (chapter_id) REFERENCES chapter (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE chapter ADD date_creation DATETIME DEFAULT NULL, CHANGE status status ENUM(\'published\', \'in_progress\')');
        $this->addSql('ALTER TABLE comment ADD date_creation DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE novel CHANGE status status ENUM(\'published\', \'unpublished\')');
        $this->addSql('ALTER TABLE novel_image CHANGE img_position img_position ENUM(\'cover\', \'banner\')');
        $this->addSql('ALTER TABLE transaction CHANGE status status ENUM("pending", "completed", "canceled")');
        $this->addSql('ALTER TABLE user ADD bio LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_novel CHANGE relation relation ENUM(\'author\')');

        // Backfill existing chapters/comments with a plausible date: the parent novel's own creation date.
        // This is an approximation for pre-existing rows only; every row created from here on gets a real timestamp.
        $this->addSql('UPDATE chapter c JOIN novel n ON n.id = c.novel_id SET c.date_creation = n.date_creation WHERE c.date_creation IS NULL');
        $this->addSql('UPDATE comment cm JOIN novel n ON n.id = cm.novel_id SET cm.date_creation = n.date_creation WHERE cm.date_creation IS NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment_like DROP FOREIGN KEY FK_8A55E25FA76ED395');
        $this->addSql('ALTER TABLE comment_like DROP FOREIGN KEY FK_8A55E25FF8697D13');
        $this->addSql('ALTER TABLE library_entry DROP FOREIGN KEY FK_18844F5FA76ED395');
        $this->addSql('ALTER TABLE library_entry DROP FOREIGN KEY FK_18844F5FB9E41394');
        $this->addSql('ALTER TABLE reading_progress DROP FOREIGN KEY FK_74F6AFF6A76ED395');
        $this->addSql('ALTER TABLE reading_progress DROP FOREIGN KEY FK_74F6AFF6B9E41394');
        $this->addSql('ALTER TABLE reading_progress DROP FOREIGN KEY FK_74F6AFF6579F4768');
        $this->addSql('DROP TABLE comment_like');
        $this->addSql('DROP TABLE library_entry');
        $this->addSql('DROP TABLE reading_progress');
        $this->addSql('ALTER TABLE novel CHANGE status status VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE chapter DROP date_creation, CHANGE status status VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user_novel CHANGE relation relation VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user DROP bio');
        $this->addSql('ALTER TABLE comment DROP date_creation');
        $this->addSql('ALTER TABLE novel_image CHANGE img_position img_position VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE transaction CHANGE status status VARCHAR(255) DEFAULT NULL');
    }
}
