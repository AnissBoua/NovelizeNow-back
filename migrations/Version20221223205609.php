<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221223205609 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category ADD id_parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1F24F7657 FOREIGN KEY (id_parent_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_64C19C1F24F7657 ON category (id_parent_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1F24F7657');
        $this->addSql('DROP INDEX IDX_64C19C1F24F7657 ON category');
        $this->addSql('ALTER TABLE category DROP id_parent_id');
    }
}
