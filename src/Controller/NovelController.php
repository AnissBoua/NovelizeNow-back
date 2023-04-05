<?php

namespace App\Controller;

use DateTime;
use App\Entity\Image;
use App\Entity\Novel;
use App\Entity\UserNovel;
use App\Entity\NovelImage;
use App\Repository\UserRepository;
use App\Repository\NovelRepository;
use App\Repository\CategoryRepository;
use App\Middleware\FileUploadMiddleware;
use App\Repository\NovelImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Middleware\NovelRelationMiddleware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\SecurityBundle\Security as SecurityAuth;
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
        NovelRelationMiddleware $novelRelationMiddleware,
        FileUploadMiddleware $fileUploadMiddleware,
        NovelImageRepository $novelImageRepository,
        CategoryRepository $categoryRepository
    )
    {
        $this->em = $em;
        $this->novelRepository = $novelRepository;
        $this->userRepository = $userRepository;
        $this->novelRelationMiddleware = $novelRelationMiddleware;
        $this->fileUploadMiddleware = $fileUploadMiddleware;
        $this->novelImageRepository = $novelImageRepository;
        $this->categoryRepository = $categoryRepository;
    }

    #[Route('/', name: 'new_novel', methods: ['POST']), Security("is_granted('IS_AUTHENTICATED_FULLY')")]
    public function post(Request $request, SerializerInterface $serializerInterface)
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


        $slugger = new AsciiSlugger();
        $novel = new Novel;
        $novel->setTitle($data->get('title'));

        $slug = $slugger->slug($data->get('title'))->lower();
        $novel->setSlug($this->findSlug($slug));

        $novel->setResume($data->get('resume'));
        $novel->setDateCreation(new DateTime());

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

        $this->em->persist($novel); 
        $this->em->persist($userNovel);
        $this->em->flush();
        $novel = $serializerInterface->serialize($novel, 'json', ['groups' => 'novel:get']);
        return new JsonResponse($novel, 200,  [], true);
    }

    #[Route('/{id}', name: 'get_novel', methods: ['GET'])]
    public function get($id, SerializerInterface $serializerInterface)
    {
        $novel = $this->novelRepository->find($id);
        if (!$novel) {
            return $this->json(['error' => 'No found id: '. $id], 404);
        }
        $novel = $serializerInterface->serialize($novel, 'json', ['groups' => 'novel:get']);
        return new JsonResponse($novel, 200,  [], true);
    }

    #[Route('/bySlug/{slug}', name: 'get_novel_by_slug', methods: ['GET'])]
    public function getBySlug($slug, SerializerInterface $serializerInterface)
    {
        $novel = $this->novelRepository->findOneBy(['slug' => $slug]);
        if (!$novel) {
            return $this->json(['error' => 'No found id: '. $slug], 404);
        }
        $novel = $serializerInterface->serialize($novel, 'json', ['groups' => 'novel:get']);
        return new JsonResponse($novel, 200,  [], true);
    }

    #[Route('/', name: 'get_all_novel', methods: ['GET'])]
    public function getAll(SerializerInterface $serializerInterface)
    {
        $novels = $this->novelRepository->findAll();
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

        if (!$this->novelRelationMiddleware->isUserAuthorized($novel, $user)) {
            return $this->json(['error' => 'Vous éte pas l\'author de cette novel du coup vous ne pouver pas le supprimer : '. $novel->getId()], 404);
        }

        $novel->setTitle($data->get('title'));
        $novel->setResume($data->get('resume'));
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

    #[Route('/{id}', name: 'delete_novel', methods: ['DELETE']), Security("is_granted('IS_AUTHENTICATED_FULLY')")]
    public function delete($id)
    {
        $novel = $this->novelRepository->find($id);

        if (!$novel) {
            return $this->json(['error' => 'No found id: '. $id], 404);
        }

        $user = $this->security->getUser();
        if (!$this->novelRelationMiddleware->isUserAuthorized($novel, $user)) {
            return $this->json(['error' => 'Vous éte pas l\'author de cette novel du coup vous ne pouver pas le supprimer : '. $novel->getId()], 404);
        }

        $this->em->remove($novel);
        $this->em->flush();

        return $this->json(['response' => 'Deleted succesfully'], 200);
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
        $destination = $this->getParameter('kernel.project_dir').'/public/uploads/novels';
        $image = $this->fileUploadMiddleware->imageUpload($image, $destination);
        if (!$image) {
            return $this->json(["error" => "L'image que vous avez essayer de uploder n'a pas etais sauvegarder"], 400);
        }

        $novelImage = $novel->getNovelImages()->filter(function ($novelImage) use ($position) {
            return $novelImage->getImgPosition() === $position;
        })->first();

        if($novelImage){
            $oldImage = $novelImage->getImage();
            $this->fileUploadMiddleware->imageDelete($oldImage, $destination);
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
