<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221223221501 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE coin_transaction (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, coins INT NOT NULL, data_info JSON NOT NULL, date_transaction DATETIME NOT NULL, INDEX IDX_6B452399A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE coin_transaction ADD CONSTRAINT FK_6B452399A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE coin_transaction DROP FOREIGN KEY FK_6B452399A76ED395');
        $this->addSql('DROP TABLE coin_transaction');
    }
}
