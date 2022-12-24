<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221223221750 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_novel (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, novel_id INT DEFAULT NULL, relation VARCHAR(255) NOT NULL, INDEX IDX_6D55844FA76ED395 (user_id), INDEX IDX_6D55844FB9E41394 (novel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_novel ADD CONSTRAINT FK_6D55844FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_novel ADD CONSTRAINT FK_6D55844FB9E41394 FOREIGN KEY (novel_id) REFERENCES novel (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_novel DROP FOREIGN KEY FK_6D55844FA76ED395');
        $this->addSql('ALTER TABLE user_novel DROP FOREIGN KEY FK_6D55844FB9E41394');
        $this->addSql('DROP TABLE user_novel');
    }
}
