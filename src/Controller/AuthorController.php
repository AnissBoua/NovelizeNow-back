<?php

namespace App\Controller;

use App\Entity\Novel;
use App\Entity\Chapter;
use App\Repository\UserRepository;
use App\Repository\FollowRepository;
use App\Repository\NovelRepository;
use App\Repository\ChapterRepository;
use App\Services\NovelCardBuilder;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security as SecurityAuth;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/author')]
class AuthorController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private NovelRepository $novelRepository,
        private ChapterRepository $chapterRepository,
        private FollowRepository $followRepository,
        private NovelCardBuilder $novelCardBuilder,
        private SecurityAuth $security,
    ) {
    }

    #[Route('/{id}', name: 'get_author', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(int $id)
    {
        $author = $this->userRepository->find($id);
        $novels = $author ? $this->novelRepository->findPublishedByAuthor($id) : [];
        // Only authors with at least one published novel get a public page.
        if (!$novels) {
            return $this->json(['error' => 'No found id: '. $id], 404);
        }

        $cards = array_map(fn(Novel $novel) => $this->novelCardBuilder->build($novel), $novels);
        usort($cards, fn($a, $b) => [$b['likesCount'], $b['publishedAt']] <=> [$a['likesCount'], $a['publishedAt']]);

        $viewer = $this->security->getUser();
        $isSelf = $viewer && $viewer->getId() === $author->getId();
        $isFollowing = $viewer && !$isSelf
            && $this->followRepository->findOneBy(['follower' => $viewer->getId(), 'author' => $author->getId()]) !== null;

        return $this->json([
            'author' => [
                'id' => $author->getId(),
                'name' => $author->getName(),
                'lastname' => $author->getLastname(),
                'username' => $author->getUsername(),
                'bio' => $author->getBio(),
                'avatar' => $author->getAvatar() ? ['filepath' => $author->getAvatar()->getFilepath()] : null,
                'isSelf' => $isSelf,
                'isFollowing' => $isFollowing,
            ],
            'stats' => [
                'novels' => count($novels),
                'likes' => array_sum(array_map(fn(Novel $novel) => $novel->getLikesCount(), $novels)),
                'followers' => $author->getFollowersCount(),
                'words' => array_sum(array_map(fn(Novel $novel) => $novel->getWordCount(), $novels)),
            ],
            'novels' => $cards,
            'recentChapters' => array_map(fn(Chapter $chapter) => [
                'id' => $chapter->getId(),
                'title' => $chapter->getTitle(),
                'number' => $this->publishedPosition($chapter),
                'date' => $chapter->getDateCreation(),
                'novel' => ['title' => $chapter->getNovel()->getTitle(), 'slug' => $chapter->getNovel()->getSlug()],
            ], $this->chapterRepository->findRecentPublishedByAuthor($id, 4)),
            'nextChapter' => $this->nextChapter($id),
            'rhythm' => $this->rhythm($novels, $cards),
            'genres' => $this->genres($novels),
            'firstPublishedAt' => min(array_map(fn(Novel $novel) => $novel->getPublishedAt(), $novels)),
            'alsoRead' => $this->alsoRead($id),
        ]);
    }

    // Position the chapter has (or will have, once published) among the novel's published chapters.
    private function publishedPosition(Chapter $chapter): int
    {
        $before = array_filter(
            $chapter->getNovel()->getPublishedChapters(),
            fn(Chapter $published) => $published->getId() < $chapter->getId()
        );
        return count($before) + 1;
    }

    private function nextChapter(int $authorId): ?array
    {
        $chapter = $this->chapterRepository->findNextScheduledByAuthor($authorId);
        if (!$chapter) {
            return null;
        }
        return [
            'publishAt' => $chapter->getPublishAt(),
            'number' => $this->publishedPosition($chapter),
            'novel' => ['title' => $chapter->getNovel()->getTitle(), 'slug' => $chapter->getNovel()->getSlug()],
        ];
    }

    // The announced rhythm of the author's most recently updated ongoing novel, if it has one.
    private function rhythm(array $novels, array $cards): ?array
    {
        $updatedAtById = array_column($cards, 'updatedAt', 'id');
        $candidates = array_filter($novels, fn(Novel $novel) => $novel->getProgress() === 'ongoing' && $novel->getRhythm());
        usort($candidates, fn($a, $b) => ($updatedAtById[$b->getId()] ?? '') <=> ($updatedAtById[$a->getId()] ?? ''));
        $novel = $candidates[0] ?? null;

        return $novel ? [
            'rhythm' => $novel->getRhythm(),
            'releaseDay' => $novel->getReleaseDay(),
            'novelTitle' => $novel->getTitle(),
        ] : null;
    }

    private function genres(array $novels): array
    {
        $genres = [];
        foreach ($novels as $novel) {
            foreach ($novel->getCategories() as $category) {
                $genres[$category->getId()] ??= ['id' => $category->getId(), 'name' => $category->getName(), 'count' => 0];
                $genres[$category->getId()]['count']++;
            }
        }
        usort($genres, fn($a, $b) => [$b['count'], $a['name']] <=> [$a['count'], $b['name']]);
        return $genres;
    }

    private function alsoRead(int $authorId): array
    {
        $result = [];
        foreach ($this->userRepository->findAuthorsAlsoRead($authorId, 3) as $row) {
            $other = $this->userRepository->find($row['author_id']);
            $otherNovels = $this->novelRepository->findPublishedByAuthor($other->getId());
            usort($otherNovels, fn(Novel $a, Novel $b) => $b->getLikesCount() <=> $a->getLikesCount());
            $topNovel = $otherNovels[0] ?? null;
            $topCategory = $topNovel?->getCategories()->first();

            $result[] = [
                'id' => $other->getId(),
                'name' => $other->getName(),
                'lastname' => $other->getLastname(),
                'avatar' => $other->getAvatar() ? ['filepath' => $other->getAvatar()->getFilepath()] : null,
                'novelTitle' => $topNovel?->getTitle(),
                'category' => $topCategory ? $topCategory->getName() : null,
            ];
        }
        return $result;
    }
}
