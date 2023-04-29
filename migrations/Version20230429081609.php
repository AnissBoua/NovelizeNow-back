<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230429081609 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE coin_transaction DROP FOREIGN KEY FK_6B452399A76ED395');
        $this->addSql('DROP TABLE coin_transaction');
        $this->addSql('ALTER TABLE cart_item DROP FOREIGN KEY FK_F0FE25271AD5CDBF');
        $this->addSql('DROP TABLE cart_item');
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B7A76ED395');
        $this->addSql('DROP TABLE cart');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cart (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, total INT NOT NULL, UNIQUE INDEX UNIQ_BA388B7A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE TABLE cart_item (id INT AUTO_INCREMENT NOT NULL, cart_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, id_item INT NOT NULL, INDEX IDX_F0FE25271AD5CDBF (cart_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cart_item ADD CONSTRAINT FK_F0FE25271AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id)');
        $this->addSql('CREATE TABLE coin_transaction (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, coins INT NOT NULL, data_info JSON NOT NULL, date_transaction DATETIME NOT NULL, INDEX IDX_6B452399A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE coin_transaction ADD CONSTRAINT FK_6B452399A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }
}
