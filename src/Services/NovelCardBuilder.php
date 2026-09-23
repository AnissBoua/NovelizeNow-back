<?php

namespace App\Services;

use App\Entity\Novel;

class NovelCardBuilder
{
    public function build(Novel $novel): array
    {
        $publishedChapters = $novel->getPublishedChapters();
        $lastUpdate = null;
        foreach ($publishedChapters as $chapter) {
            if ($lastUpdate === null || $chapter->getDateCreation() > $lastUpdate) {
                $lastUpdate = $chapter->getDateCreation();
            }
        }
        $author = $novel->getAuthor();
        $cover = $novel->getCover();
        $firstCategory = $novel->getCategories()->first();

        return [
            'id' => $novel->getId(),
            'slug' => $novel->getSlug(),
            'title' => $novel->getTitle(),
            'resume' => $novel->getResume(),
            'cover' => $cover ? ['filepath' => $cover->getFilepath()] : null,
            'author' => $author ? trim($author->getName() . ' ' . $author->getLastname()) : null,
            'progress' => $novel->getProgress(),
            'likesCount' => $novel->getLikesCount(),
            'chapterCount' => count($publishedChapters),
            'hasFreeChapter' => count($publishedChapters) > 0,
            'firstChapterId' => isset($publishedChapters[0]) ? $publishedChapters[0]->getId() : null,
            'firstCategory' => $firstCategory ? ['id' => $firstCategory->getId(), 'name' => $firstCategory->getName()] : null,
            'publishedAt' => $novel->getPublishedAt(),
            'updatedAt' => $lastUpdate,
        ];
    }
}
