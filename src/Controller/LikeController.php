<?php

namespace App\Controller;

use App\Entity\Like;
use App\Entity\User;
use App\Entity\Novel;
use App\Entity\Offer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\SecurityBundle\Security as SecurityAuth;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

#[Route('/like')]
class LikeController extends AbstractController
{
    public function __construct(EntityManagerInterface $em, SerializerInterface $serializer,private SecurityAuth $security)
    {
        $this->em = $em;
        $this->serializer = $serializer;
    }

    #[Route('/', name: 'addLike', methods: ['POST']), Security("is_granted('IS_AUTHENTICATED_FULLY')")]
    public function addLike(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        $user = $this->em->getRepository(User::class)->findOneBy(["id"=>$this->security->getUser()->getId()]);
        $novel = $this->em->getRepository(Novel::class)->findOneBy(["id"=>$data["novel"]]);
        if (!$novel) {
            return $this->json(['error' => 'Novel not found.'], Response::HTTP_NOT_FOUND);
        }

        $like = $this->em->getRepository(Like::class)->findOneBy(["user"=>$user->getId(),"novel"=>$data["novel"]]);

        if($like === null){
            $like = new Like();
            $like->setUser($user);
            $like->setNovel($novel);
            $this->em->persist($like);
            $this->em->flush();
            $like = $this->serializer->serialize($like, 'json', ['groups' => 'like:get']);
            return new JsonResponse($like, 201,  [], true);
        } else {
            $this->em->remove($like);
            $this->em->flush();
            return $this->json(['response' => 'Deleted succesfully'], 200);
        }
    }

    #[Route('/count/{id}', name: 'countLikes', methods: ['GET'])]
    public function countbynovel($id)
    {
        $count = $this->em->getRepository(Like::class)->createQueryBuilder('l')
            ->select('COUNT(l.id)')
            ->join('l.novel', 'n')
            ->where('n.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleScalarResult();

        return new JsonResponse(['count' => $count]);
    }

    #[Route('/liked/{id}', name: 'alreadyLiked', methods: ['GET']), Security("is_granted('IS_AUTHENTICATED_FULLY')")]
    public function novelAlreadyLiked($id)
    {
        $user = $this->em->getRepository(User::class)->findOneBy(["id"=>$this->security->getUser()->getId()]);
        $like = $this->em->getRepository(Like::class)->findOneBy(["user"=>$user->getId(),"novel"=>$id]);
        if ($like){
            return new JsonResponse(['liked' => true]);
        } else {
            return new JsonResponse(['liked' => false]);
        }
    }
}
