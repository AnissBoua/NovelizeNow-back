<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Moves chapter content off the Page entity and onto the Chapter itself,
 * concatenating each chapter's existing pages (in their pageState order)
 * into the new content/html columns before dropping the page table.
 */
final class Version20260916223724 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Move page content directly onto Chapter and remove the page table.';
    }

    public function up(Schema $schema): void
    {
        $this->connection->executeStatement('ALTER TABLE chapter ADD content LONGTEXT DEFAULT NULL, ADD html LONGTEXT DEFAULT NULL');

        $chapters = $this->connection->fetchAllAssociative('SELECT id, page_state FROM chapter');
        foreach ($chapters as $chapterRow) {
            $pageIds = $chapterRow['page_state'] ? array_values(unserialize($chapterRow['page_state'])) : [];
            if (!$pageIds) {
                continue;
            }

            $placeholders = implode(',', array_fill(0, count($pageIds), '?'));
            $pages = $this->connection->fetchAllAssociative(
                "SELECT id, content, html FROM page WHERE id IN ($placeholders)",
                $pageIds
            );
            $byId = [];
            foreach ($pages as $page) {
                $byId[$page['id']] = $page;
            }

            $contentParts = [];
            $htmlParts = [];
            foreach ($pageIds as $pageId) {
                if (isset($byId[$pageId])) {
                    $contentParts[] = $byId[$pageId]['content'];
                    $htmlParts[] = $byId[$pageId]['html'];
                }
            }

            $this->connection->executeStatement(
                'UPDATE chapter SET content = ?, html = ? WHERE id = ?',
                [implode("\n\n", $contentParts), implode('', $htmlParts), $chapterRow['id']]
            );
        }

        $this->connection->executeStatement('ALTER TABLE page DROP FOREIGN KEY FK_140AB620579F4768');
        $this->connection->executeStatement('DROP TABLE page');
        $this->connection->executeStatement('ALTER TABLE chapter DROP page_state, CHANGE status status ENUM(\'published\', \'in_progress\')');

        $this->addSql('ALTER TABLE novel CHANGE status status ENUM(\'published\', \'unpublished\')');
        $this->addSql('ALTER TABLE novel_image CHANGE img_position img_position ENUM(\'cover\', \'banner\')');
        $this->addSql('ALTER TABLE transaction CHANGE status status ENUM("pending", "completed", "canceled")');
        $this->addSql('ALTER TABLE user_novel CHANGE relation relation ENUM(\'author\')');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE TABLE page (id INT AUTO_INCREMENT NOT NULL, chapter_id INT DEFAULT NULL, content LONGTEXT CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, html LONGTEXT CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, INDEX IDX_140AB620579F4768 (chapter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE page ADD CONSTRAINT FK_140AB620579F4768 FOREIGN KEY (chapter_id) REFERENCES chapter (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE novel_image CHANGE img_position img_position VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE chapter ADD page_state LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', DROP content, DROP html, CHANGE status status VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user_novel CHANGE relation relation VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE novel CHANGE status status VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE transaction CHANGE status status VARCHAR(255) DEFAULT NULL');
    }
}
