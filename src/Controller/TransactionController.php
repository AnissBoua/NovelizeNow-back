<?php

namespace App\Controller;

use App\Entity\Transaction;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\SecurityBundle\Security as SecurityAuth;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/transaction')]
class TransactionController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private SerializerInterface $serializer,
        private SecurityAuth $security,
    ) {
    }

    #[Route('/me', name: 'my_transactions', methods: ['GET']), Security("is_granted('IS_AUTHENTICATED_FULLY')")]
    public function myTransactions()
    {
        $user = $this->security->getUser();

        $transactions = $this->em->getRepository(Transaction::class)->findBy(
            ['user' => $user, 'status' => 'completed'],
            ['dateTransaction' => 'DESC']
        );

        $json = $this->serializer->serialize($transactions, 'json', ['groups' => 'transaction:get']);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }
}
