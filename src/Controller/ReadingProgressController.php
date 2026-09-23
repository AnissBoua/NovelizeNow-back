<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Chapter;
use App\Entity\ReadingProgress;
use App\Repository\ReadingProgressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\SecurityBundle\Security as SecurityAuth;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

#[Route('/reading-progress')]
class ReadingProgressController extends AbstractController
{
    public function __construct(
        EntityManagerInterface $em,
        ReadingProgressRepository $readingProgressRepository,
        private SecurityAuth $security
    ) {
        $this->em = $em;
        $this->readingProgressRepository = $readingProgressRepository;
    }

    #[Route('/', name: 'save_reading_progress', methods: ['POST']), Security("is_granted('IS_AUTHENTICATED_FULLY')")]
    public function save(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $chapter = $this->em->getRepository(Chapter::class)->find($data['chapter'] ?? null);
        if (!$chapter) {
            return $this->json(['error' => 'Chapter not found.'], Response::HTTP_NOT_FOUND);
        }

        /** @var \App\Entity\User $user */
        $user = $this->security->getUser();
        $user = $this->em->getRepository(User::class)->find($user->getId());
        $novel = $chapter->getNovel();

        $progress = $this->readingProgressRepository->findOneBy(['user' => $user, 'novel' => $novel]);
        if (!$progress) {
            $progress = new ReadingProgress();
            $progress->setUser($user);
            $progress->setNovel($novel);
        }
        $progress->setChapter($chapter);
        $progress->setUpdatedAt(new \DateTime());

        $this->em->persist($progress);
        $this->em->flush();

        return $this->json(['response' => 'ok'], Response::HTTP_OK);
    }

    #[Route('/me', name: 'get_my_reading_progress', methods: ['GET']), Security("is_granted('IS_AUTHENTICATED_FULLY')")]
    public function getMine(Request $request, SerializerInterface $serializer)
    {
        /** @var \App\Entity\User $user */
        $user = $this->security->getUser();
        $user = $this->em->getRepository(User::class)->find($user->getId());

        $limit = $request->query->has('limit') ? max(1, (int) $request->query->get('limit')) : null;
        $progressList = $this->readingProgressRepository->findBy(['user' => $user], ['updatedAt' => 'DESC'], $limit);

        $result = [];
        foreach ($progressList as $progress) {
            $novel = $progress->getNovel();
            $chapter = $progress->getChapter();
            $publishedChapters = $novel->getPublishedChapters();

            $chapterIndex = null;
            foreach ($publishedChapters as $index => $ch) {
                if ($ch->getId() === $chapter->getId()) {
                    $chapterIndex = $index;
                    break;
                }
            }
            if ($chapterIndex === null) {
                continue;
            }

            $cover = $novel->getCover();
            $result[] = [
                'novel' => [
                    'id' => $novel->getId(),
                    'slug' => $novel->getSlug(),
                    'title' => $novel->getTitle(),
                    'cover' => $cover ? json_decode($serializer->serialize($cover, 'json', ['groups' => 'home:get'])) : null,
                ],
                'chapterId' => $chapter->getId(),
                'chapterTitle' => $chapter->getTitle(),
                'chapterIndex' => $chapterIndex,
                'totalChapters' => count($publishedChapters),
            ];
        }

        return $this->json($result, Response::HTTP_OK);
    }
}
