<?php

namespace App\Controller;

use Exception;
use App\Entity\User;
use App\Entity\Novel;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security as SecurityAuth;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/order')]
class OrderController extends AbstractController
{

    public function __construct(
        SecurityAuth $securityAuth, 
        EntityManagerInterface $em,
        private SerializerInterface $serializer
    ) {
        $this->security = $securityAuth;
        $this->em = $em;
    }

    #[Route('/', name: 'new_order', methods: ['POST']), Security("is_granted('IS_AUTHENTICATED_FULLY')")]
    public function create_order(Request $request)
    {
        /** @var \App\Entity\User $user */
        $user = $this->security->getUser();
        if(!$user) {
            throw new Exception('Vous devez être connecté pour effectuer cette action.');
        }
        $user = $this->em->getRepository(User::class)->find($user->getId());

        $data = json_decode($request->getContent(), false);
        $novel = $this->em->getRepository(Novel::class)->find($data->novel);
        if ($novel->getPrice() > $user->getCoins()) {
            throw new Exception('You don\'t have enough coins to buy this novel.');
        }

        // Check if user already bought this novel
        $order = $this->em->getRepository(Order::class)->findOneBy([
            'user' => $user,
            'novel' => $novel
        ]);

        $order = new Order();
        $order->setUser($user);
        $order->setNovel($novel);
        $order->setCoins($novel->getPrice());
        $order->setDateOrder(new \DateTime());

        $coins = $user->getCoins() - $novel->getPrice();
        $user->setCoins($coins);

        $author = $novel->getAuthor();
        $author->setCoins($author->getCoins() + $novel->getPrice());

        $this->em->persist($order);
        $this->em->persist($user);
        $this->em->persist($author);
        $this->em->flush();

        $order = json_decode($this->serializer->serialize($order, 'json', ['groups' => 'order:post']));


        return $this->json([
            'order' => $order,
            'user_coins' => $coins
        ], 200);
    }

    #[Route('/novel/{slug}', name: 'verify_user_bought_novel', methods: ['GET']), Security("is_granted('IS_AUTHENTICATED_FULLY')")]
    public function userBoughtNovel($slug){
        /** @var \App\Entity\User $user */
        $user = $this->security->getUser();
        if(!$user) {
            throw new Exception('Vous devez être connecté pour effectuer cette action.');
        }

        $novel = $this->em->getRepository(Novel::class)->findOneBy([
            'slug' => $slug
        ]);
        if (!$novel) {
            throw new Exception('Cette novel n\'existe pas.', 404);
        }

        $order = $this->em->getRepository(Order::class)->findOneBy([
            'user' => $user,
            'novel' => $novel
        ]);

        if (!$order) {
            return $this->json([
                'bought' => false
            ], 200);
        }

        return $this->json([
            'bought' => true
        ], 200);
    }
}
