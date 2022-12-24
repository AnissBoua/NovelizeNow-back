<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221223212032 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE novel_image ADD image_id INT DEFAULT NULL, ADD novel_id INT DEFAULT NULL, ADD img_position VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE novel_image ADD CONSTRAINT FK_E0AA285E3DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE novel_image ADD CONSTRAINT FK_E0AA285EB9E41394 FOREIGN KEY (novel_id) REFERENCES novel (id)');
        $this->addSql('CREATE INDEX IDX_E0AA285E3DA5256D ON novel_image (image_id)');
        $this->addSql('CREATE INDEX IDX_E0AA285EB9E41394 ON novel_image (novel_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE novel_image DROP FOREIGN KEY FK_E0AA285E3DA5256D');
        $this->addSql('ALTER TABLE novel_image DROP FOREIGN KEY FK_E0AA285EB9E41394');
        $this->addSql('DROP INDEX IDX_E0AA285E3DA5256D ON novel_image');
        $this->addSql('DROP INDEX IDX_E0AA285EB9E41394 ON novel_image');
        $this->addSql('ALTER TABLE novel_image DROP image_id, DROP novel_id, DROP img_position');
    }
}
