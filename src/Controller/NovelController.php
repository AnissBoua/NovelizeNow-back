<?php

namespace App\Controller;

use App\Entity\Image;
use DateTime;
use App\Entity\Novel;
use App\Entity\NovelImage;
use App\Entity\UserNovel;
use App\Middleware\FileUploadMiddleware;
use App\Middleware\NovelRelationMiddleware;
use App\Repository\CategoryRepository;
use App\Repository\NovelRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security as SecurityAuth;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;

#[Route('/novel')]
class NovelController extends AbstractController
{
    public function __construct(
        EntityManagerInterface $em, 
        NovelRepository $novelRepository, 
        UserRepository $userRepository, 
        private SecurityAuth $security,
        NovelRelationMiddleware $novelRelationMiddleware,
        FileUploadMiddleware $fileUploadMiddleware
    )
    {
        $this->em = $em;
        $this->novelRepository = $novelRepository;
        $this->userRepository = $userRepository;
        $this->novelRelationMiddleware = $novelRelationMiddleware;
        $this->fileUploadMiddleware = $fileUploadMiddleware;
    }

    #[Route('/', name: 'new_novel', methods: ['POST']), Security("is_granted('IS_AUTHENTICATED_FULLY')")]
    public function post(Request $request, CategoryRepository $categoryRepo, SerializerInterface $serializerInterface)
    {
        // $data = json_decode($request->getContent(), true);
        $data = $request->request;
        $files = $request->files;

        $cover = $files->get('cover');
        $destination = $this->getParameter('kernel.project_dir').'/public/uploads/novels';
        $cover = $this->fileUploadMiddleware->imageUpload($cover, $destination);

        if (!$cover) {
            return $this->json(["error" => "L'image que vous avez essayer de uploder n'a pas etais sauvegarder"], 404);
        }

        $slugger = new AsciiSlugger();
        $novel = new Novel;
        $novel->setTitle($data->get('title'));

        $slug = $slugger->slug($data->get('title'));
        $novel->setSlug($this->findSlug($slug));

        $novel->setResume($data->get('resume'));
        $novel->setDateCreation(new DateTime());

        $categories = $data->all('category');
        foreach ($categories as $category) {
            $category = $categoryRepo->findOneBy(['id' => $category]);
            $novel->addCategory($category);
        }

        /** @var \App\Entity\User $user */
        $user = $this->security->getUser();
        $user = $this->userRepository->find($user->getId());

        $userNovel = new UserNovel;
        $userNovel->setNovel($novel);
        $userNovel->setUser($user); 
        $userNovel->setRelation('author');
  
        
        $novelImage = new NovelImage;
        $novelImage->setImage($cover);
        $novelImage->setNovel($novel);
        $novelImage->setImgPosition('cover');

        $novel->addNovelImage($novelImage);

        $this->em->persist($novel); 
        $this->em->persist($userNovel);
        $this->em->persist($novelImage);
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

    #[Route('/', name: 'get_all_novel', methods: ['GET'])]
    public function getAll(SerializerInterface $serializerInterface)
    {
        $novels = $this->novelRepository->findAll();
        $novels = $serializerInterface->serialize($novels, 'json', ['groups' => 'novel:get']);
        return new JsonResponse($novels, 200,  [], true);
    }

    #[Route('/{id}', name: 'edit_novel', methods: ['PUT']),  Security("is_granted('IS_AUTHENTICATED_FULLY')")]
    public function edit($id, Request $request, SerializerInterface $serializerInterface)
    {
        $data = json_decode($request->getContent(), true);
        
        $novel = $this->novelRepository->find($id);
        if (!$novel) {
            return $this->json(['error' => 'No found id: '. $id], 404);
        }

        $user = $this->security->getUser();

        if (!$this->novelRelationMiddleware->isUserAuthorized($novel, $user)) {
            return $this->json(['error' => 'Vous éte pas l\'author de cette novel du coup vous ne pouver pas le supprimer : '. $novel->getId()], 404);
        }

        $novel->setTitle($data['title']);
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
}
