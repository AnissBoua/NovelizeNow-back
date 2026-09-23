<?php

namespace App\Controller;

use DateTime;
use Exception;
use App\Entity\Image;
use App\Entity\Novel;
use App\Entity\Order;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\UserNovel;
use App\Entity\NovelImage;
use App\Entity\CommentLike;
use App\Entity\LibraryEntry;
use App\Entity\ReadingProgress;
use App\Entity\Follow;
use App\Repository\UserRepository;
use App\Repository\NovelRepository;
use App\Services\FileUploadService;
use App\Repository\CategoryRepository;
use App\Services\NovelRelationService;
use App\Repository\NovelImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\SecurityBundle\Security as SecurityAuth;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/novel')]
class NovelController extends AbstractController
{
    public function __construct(
        EntityManagerInterface $em, 
        NovelRepository $novelRepository, 
        UserRepository $userRepository, 
        private SecurityAuth $security,
        NovelRelationService $novelRelationService,
        FileUploadService $fileUploadService,
        NovelImageRepository $novelImageRepository,
        CategoryRepository $categoryRepository
    )
    {
        $this->em = $em;
        $this->novelRepository = $novelRepository;
        $this->userRepository = $userRepository;
        $this->novelRelationService = $novelRelationService;
        $this->fileUploadService = $fileUploadService;
        $this->novelImageRepository = $novelImageRepository;
        $this->categoryRepository = $categoryRepository;
    }

    #[Route('/', name: 'new_novel', methods: ['POST']), Security("is_granted('IS_AUTHENTICATED_FULLY')")]
    public function post(Request $request, SerializerInterface $serializerInterface,ValidatorInterface $validator)
    {
        // $data = json_decode($request->getContent(), true);
        $data = $request->request;
        $files = $request->files;

        /** @var \App\Entity\User $user */
        $user = $this->security->getUser();
        if(!$user) {
            return $this->json(['error' => 'Vous devez être connecté pour créer un nouveau roman'], 401);
        }
        $user = $this->userRepository->find($user->getId());


        // Nolel
        $slugger = new AsciiSlugger();
        $novel = new Novel;
        $novel->setTitle($data->get('title'));

        $slug = $slugger->slug($data->get('title'))->lower();
        $novel->setSlug($this->findSlug($slug));

        $novel->setResume($data->get('resume'));
        $novel->setPrice($data->get('price'));
        if (!$this->applyRhythmAndProgress($novel, $data)) {
            return $this->json(['error' => 'Rythme ou état invalide.'], 400);
        }
        $novel->setDateCreation(new DateTime());
        $errors = $validator->validate($novel);
        if (count($errors) > 0) {
            return $this->json(['error' => $errors], 400);
        }
        $this->em->persist($novel); 

        // Relations
        $categories = $data->all('category');
        $this->setNoveltCategories($novel, $categories);

        if ($files->get('cover')) {
            $cover = $files->get('cover');
            $cover = $this->setNovelImages($novel, $cover, 'cover');
            $novel->addNovelImage($cover);
        }

        if($files->get('banner')) {
            $banner = $files->get('banner');
            $banner = $this->setNovelImages($novel, $banner, 'banner');
            $novel->addNovelImage($banner);
        }
        

        $userNovel = new UserNovel;
        $userNovel->setNovel($novel);
        $userNovel->setUser($user); 
        $userNovel->setRelation('author');
  
        $novel->addUserNovel($userNovel);

        $this->em->persist($userNovel);
        $this->em->flush();
        $novel = $serializerInterface->serialize($novel, 'json', ['groups' => 'novel:get']);
        return new JsonResponse($novel, 201,  [], true);
    }

    #[Route('/search', name: 'search_novel', methods: ['GET'])]
    public function search(Request $request, SerializerInterface $serializerInterface)
    {
        if (!$request->query->get('search')) {
            return $this->json(['error' => 'No search'], 400);
        }
        $search = $request->query->get('search');
        $novels = $this->novelRepository->search($search);
        $categories = $this->em->getRepository(Category::class)->createQueryBuilder('c')
            ->select('c.id, c.name, c.icon')
            ->where('c.name LIKE :val')
            ->setParameter('val', '%'.$search.'%')
            ->orderBy('c.name', 'ASC')
            ->setMaxResults(3)
            ->getQuery()
            ->getArrayResult();

        // Only people with a published novel have an author page to link to.
        $authors = $this->userRepository->createQueryBuilder('u')
            ->select('u.id, u.name, u.lastname, a.filepath AS avatar, COUNT(DISTINCT n.id) AS novelCount')
            ->join('u.userNovels', 'un', 'WITH', "un.relation = 'author'")
            ->join('un.novel', 'n', 'WITH', 'n.publishedAt IS NOT NULL')
            ->leftJoin('u.avatar', 'a')
            ->where("CONCAT(CONCAT(u.name, ' '), u.lastname) LIKE :val OR u.username LIKE :val")
            ->setParameter('val', '%'.$search.'%')
            ->groupBy('u.id')
            ->addGroupBy('a.id')
            ->setMaxResults(3)
            ->getQuery()
            ->getArrayResult();

        $novels = json_decode($serializerInterface->serialize($novels, 'json', ['groups' => 'novel:get']));
        return $this->json(['novels' => $novels, 'categories' => $categories, 'authors' => $authors]);
    }

    #[Route('/{id}', name: 'get_novel', methods: ['GET'])]
    public function get($id, SerializerInterface $serializerInterface)
    {
        $novel = $this->novelRepository->find($id);
        if (!$novel || !$this->canView($novel)) {
            return $this->json(['error' => 'No found id: '. $id], 404);
        }
        $novel = $serializerInterface->serialize($novel, 'json', ['groups' => 'novel:get']);
        return new JsonResponse($novel, 200,  [], true);
    }

    #[Route('/bySlug/{slug}', name: 'get_novel_by_slug', methods: ['GET'])]
    public function getBySlug($slug, SerializerInterface $serializerInterface)
    {
        $novel = $this->novelRepository->findOneBy(['slug' => $slug]);
        if (!$novel || !$this->canView($novel)) {
            return $this->json(['error' => 'No found id: '. $slug], 404);
        }

        $novel = $serializerInterface->serialize($novel, 'json', ['groups' => 'novel:get']);
        $novel = json_decode($novel);
        $novel->isAuthor = false;
        $novel->userBought = false;
        $novel->inLibrary = false;
        $novel->readingProgress = null;
        $novel->author->isFollowing = false;
        /** @var \App\Entity\User $user */
        $user = $this->security->getUser();
        if($user) {
            $user = $this->userRepository->find($user->getId());
            if ($user->getId() == $novel->author->id) {
                $novel->isAuthor = true;
            }

            $follow = $this->em->getRepository(Follow::class)->findOneBy([
                'follower' => $user->getId(),
                'author' => $novel->author->id,
            ]);
            $novel->author->isFollowing = (bool) $follow;

            $order = $this->em->getRepository(Order::class)->findOneBy([
                'user' => $user->getId(),
                'novel' => $novel->id
            ]);
            if ($order) {
                $novel->userBought = true;
            }

            $libraryEntry = $this->em->getRepository(LibraryEntry::class)->findOneBy([
                'user' => $user->getId(),
                'novel' => $novel->id,
            ]);
            $novel->inLibrary = (bool) $libraryEntry;

            $progress = $this->em->getRepository(ReadingProgress::class)->findOneBy([
                'user' => $user->getId(),
                'novel' => $novel->id,
            ]);
            if ($progress) {
                $chapterList = $novel->isAuthor ? $novel->chapters : $novel->publishedChapters;
                foreach ($chapterList as $index => $ch) {
                    if ($ch->id === $progress->getChapter()->getId()) {
                        $novel->readingProgress = [
                            'chapterId' => $ch->id,
                            'chapterTitle' => $ch->title,
                            'chapterIndex' => $index,
                        ];
                        break;
                    }
                }
            }
        }

        $novel->comments = $this->em->getRepository(Comment::class)->findBy([
            'novel' => $novel->id,
            'comment' => null,
            'chapter' => null,
        ], ['id' => 'DESC']);

        $likedCommentIds = [];
        if ($user) {
            $allCommentIds = array_map(fn($c) => $c->getId(), $novel->comments);
            foreach ($this->em->getRepository(Comment::class)->findBy(['comment' => $allCommentIds]) as $reply) {
                $allCommentIds[] = $reply->getId();
            }
            if ($allCommentIds) {
                foreach ($this->em->getRepository(CommentLike::class)->findBy(['user' => $user->getId(), 'comment' => $allCommentIds]) as $commentLike) {
                    $likedCommentIds[] = $commentLike->getComment()->getId();
                }
            }
        }

        foreach ($novel->comments as $key => $comment) {
            $comment = json_decode($serializerInterface->serialize($comment, 'json', ['groups' => 'novel:get']));
            $comment->isLiked = in_array($comment->id, $likedCommentIds);

            $comment->comments = $this->em->getRepository(Comment::class)->findBy([
                    'comment' => $comment->id
                ], ['id' => 'DESC']);

            $comment->comments = json_decode($serializerInterface->serialize($comment->comments, 'json', ['groups' => 'novel:get']));
            foreach ($comment->comments as $reply) {
                $reply->isLiked = in_array($reply->id, $likedCommentIds);
            }

            $novel->comments[$key] = $comment;
        }

        $novel = json_encode($novel);
        return new JsonResponse($novel, 200,  [], true);
    }

    #[Route('/', name: 'get_all_novel', methods: ['GET'])]
    public function getAll(SerializerInterface $serializerInterface)
    {
        $novels = $this->novelRepository->createQueryBuilder('n')
            ->where('n.publishedAt IS NOT NULL')
            ->getQuery()
            ->getResult();
        $novels = $serializerInterface->serialize($novels, 'json', ['groups' => 'novel:get']);
        return new JsonResponse($novels, 200,  [], true);
    }

    #[Route('/{id}', name: 'edit_novel', methods: ['POST']),  Security("is_granted('IS_AUTHENTICATED_FULLY')")]
    public function edit($id, Request $request, SerializerInterface $serializerInterface)
    {
        // $data = json_decode($request->getContent(), true);
        $data = $request->request;
        $files = $request->files;
        
        $novel = $this->novelRepository->find($id);
        if (!$novel) {
            return $this->json(['error' => 'No found id: '. $id], 404);
        }

        $user = $this->security->getUser();

        if (!$this->novelRelationService->isUserAuthorized($novel, $user)) {
            return $this->json(['error' => 'Vous éte pas l\'author de cette novel du coup vous ne pouver pas le supprimer : '. $novel->getId()], 401);
        }

        $novel->setTitle($data->get('title'));
        $novel->setResume($data->get('resume'));
        $novel->setPrice($data->get('price'));
        if (!$this->applyRhythmAndProgress($novel, $data)) {
            return $this->json(['error' => 'Rythme ou état invalide.'], 400);
        }
        // handle update cover image
        if ($files->get('cover')) {
            $cover = $files->get('cover');
            $cover = $this->setNovelImages($novel, $cover, 'cover');
            $novel->addNovelImage($cover);
        }

        if ($files->get('banner')) {
            $banner = $files->get('banner');
            $banner = $this->setNovelImages($novel, $banner, 'banner');
            $novel->addNovelImage($banner);
        }

        $categories = $data->all('category');
        $this->setNoveltCategories($novel, $categories);

        $novel->setDateUpdate(new DateTime());
        $this->em->persist($novel);
        $this->em->flush();
        $novel = $serializerInterface->serialize($novel, 'json', ['groups' => 'novel:edit']);
        return new JsonResponse($novel, 200,  [], true);
    }

    #[Route('/{id}/visibility', name: 'toggle_novel_visibility', methods: ['POST']), Security("is_granted('IS_AUTHENTICATED_FULLY')")]
    public function toggleVisibility($id)
    {
        $novel = $this->novelRepository->find($id);
        if (!$novel) {
            return $this->json(['error' => 'No found id: '. $id], 404);
        }
        if (!$this->novelRelationService->isUserAuthorized($novel, $this->security->getUser())) {
            return $this->json(['error' => 'Forbidden.'], 403);
        }

        $novel->setPublishedAt($novel->isPublished() ? null : new DateTime());
        $this->em->flush();

        return $this->json(['publishedAt' => $novel->getPublishedAt()], 200);
    }

    #[Route('/{id}', name: 'delete_novel', methods: ['DELETE']), Security("is_granted('IS_AUTHENTICATED_FULLY')")]
    public function delete($id)
    {
        $novel = $this->novelRepository->find($id);

        if (!$novel) {
            return $this->json(['error' => 'No found id: '. $id], 404);
        }

        $user = $this->security->getUser();
        if (!$this->novelRelationService->isUserAuthorized($novel, $user)) {
            return $this->json(['error' => 'Vous éte pas l\'author de cette novel du coup vous ne pouver pas le supprimer : '. $novel->getId()], 401);
        }

        $this->em->remove($novel);
        $this->em->flush();

        return $this->json(['response' => 'Deleted succesfully'], 200);
    }

    private function canView(Novel $novel): bool
    {
        return $novel->isPublished() || $this->novelRelationService->isUserAuthorized($novel, $this->security->getUser());
    }

    private function applyRhythmAndProgress(Novel $novel, $data): bool
    {
        $progress = $data->get('progress', $novel->getProgress() ?? 'ongoing');
        $rhythm = $data->get('rhythm', $novel->getRhythm()) ?: null;
        $releaseDay = $data->get('releaseDay', $novel->getReleaseDay()) ?: null;
        $releaseDay = $releaseDay === null ? null : (int) $releaseDay;

        if (!in_array($progress, ['ongoing', 'paused', 'completed'], true)
            || ($rhythm !== null && !in_array($rhythm, ['weekly', 'biweekly', 'irregular'], true))
            || ($releaseDay !== null && ($releaseDay < 1 || $releaseDay > 7))) {
            return false;
        }

        $novel->setProgress($progress);
        $novel->setRhythm($rhythm);
        $novel->setReleaseDay(in_array($rhythm, ['weekly', 'biweekly'], true) ? $releaseDay : null);

        return true;
    }

    private function findSlug($slug, $int = 1){

        if ($this->novelRepository->findOneBy(['slug' => $slug])) {
            $newslug = $slug . '-' . $int;
            if ($this->novelRepository->findOneBy(['slug' => $newslug])) {
                $slug = $this->findSlug($slug, ($int + 1));
            } else {
                $slug = $slug . '-' . $int;
            }
        }
        return $slug;
    }

    private function setNoveltCategories($novel, $categories): void
    {
        foreach ($categories as $category) {
            
            $category = $this->categoryRepository->findOneBy(['id' => $category]);
            // remove old categories
            foreach ($novel->getCategories() as $oldCategory) {
                if (!in_array($oldCategory->getId(), $categories)) {
                    $novel->removeCategory($oldCategory);
                }
            }
            // add new categories
            if (!$novel->getCategories()->contains($category)) {
                $novel->addCategory($category);
            }
        }
    }

    // function for upload images, images can be of different types (cover, banner, etc.)
    private function setNovelImages($novel, $image, $position) : NovelImage
    {
        $destination = '/uploads/novels';
        $image = $this->fileUploadService->imageUpload($image, $destination);
        if (!$image) {
            throw new Exception("L'image que vous avez essayer de uploder n'a pas etais sauvegarder, verifier que le fichier est bien une image de type jpg, jpeg ou png");
        }

        $novelImage = $novel->getNovelImages()->filter(function ($novelImage) use ($position) {
            return $novelImage->getImgPosition() === $position;
        })->first();

        if($novelImage){
            $oldImage = $novelImage->getImage();
            $this->fileUploadService->imageDelete($oldImage, $destination);
            $novelImage->setImage($image);
            $this->em->persist($novelImage);
            return $novelImage;
        } else {
            $novelImage = new NovelImage;
            $novelImage->setImage($image);
            $novelImage->setImgPosition($position);
            $novelImage->setNovel($novel);
            $this->em->persist($novelImage);
            return $novelImage;
        }
    }
}
