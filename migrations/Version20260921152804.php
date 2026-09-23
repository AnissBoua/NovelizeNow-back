<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260921152804 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE follow (id INT AUTO_INCREMENT NOT NULL, follower_id INT NOT NULL, author_id INT NOT NULL, date_creation DATETIME NOT NULL, INDEX IDX_68344470AC24F853 (follower_id), INDEX IDX_68344470F675F31B (author_id), UNIQUE INDEX follow_unique (follower_id, author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE follow ADD CONSTRAINT FK_68344470AC24F853 FOREIGN KEY (follower_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE follow ADD CONSTRAINT FK_68344470F675F31B FOREIGN KEY (author_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE chapter CHANGE status status ENUM(\'published\', \'in_progress\')');
        $this->addSql('ALTER TABLE novel CHANGE status status ENUM(\'published\', \'unpublished\')');
        $this->addSql('ALTER TABLE novel_image CHANGE img_position img_position ENUM(\'cover\', \'banner\')');
        $this->addSql('ALTER TABLE transaction CHANGE status status ENUM("pending", "completed", "canceled")');
        $this->addSql('ALTER TABLE user_novel CHANGE relation relation ENUM(\'author\')');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE follow DROP FOREIGN KEY FK_68344470AC24F853');
        $this->addSql('ALTER TABLE follow DROP FOREIGN KEY FK_68344470F675F31B');
        $this->addSql('DROP TABLE follow');
        $this->addSql('ALTER TABLE novel CHANGE status status VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user_novel CHANGE relation relation VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE chapter CHANGE status status VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE transaction CHANGE status status VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE novel_image CHANGE img_position img_position VARCHAR(255) DEFAULT NULL');
    }
}
