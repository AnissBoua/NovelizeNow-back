<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Novel;
use App\Entity\LibraryEntry;
use App\Repository\LibraryEntryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\SecurityBundle\Security as SecurityAuth;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

#[Route('/library')]
class LibraryController extends AbstractController
{
    public function __construct(
        EntityManagerInterface $em,
        SerializerInterface $serializer,
        LibraryEntryRepository $libraryEntryRepository,
        private SecurityAuth $security
    ) {
        $this->em = $em;
        $this->serializer = $serializer;
        $this->libraryEntryRepository = $libraryEntryRepository;
    }

    #[Route('/', name: 'toggle_library', methods: ['POST']), Security("is_granted('IS_AUTHENTICATED_FULLY')")]
    public function toggle(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        /** @var \App\Entity\User $user */
        $user = $this->security->getUser();
        $user = $this->em->getRepository(User::class)->find($user->getId());
        $novel = $this->em->getRepository(Novel::class)->find($data['novel'] ?? null);
        if (!$novel) {
            return $this->json(['error' => 'Novel not found.'], Response::HTTP_NOT_FOUND);
        }

        $entry = $this->libraryEntryRepository->findOneBy(['user' => $user, 'novel' => $novel]);

        if ($entry) {
            $this->em->remove($entry);
            $this->em->flush();
            return $this->json(['inLibrary' => false], Response::HTTP_OK);
        }

        $entry = new LibraryEntry();
        $entry->setUser($user);
        $entry->setNovel($novel);
        $entry->setDateCreation(new \DateTime());
        $this->em->persist($entry);
        $this->em->flush();

        return $this->json(['inLibrary' => true], Response::HTTP_CREATED);
    }

    #[Route('/me', name: 'get_library', methods: ['GET']), Security("is_granted('IS_AUTHENTICATED_FULLY')")]
    public function mine()
    {
        /** @var \App\Entity\User $user */
        $user = $this->security->getUser();

        $entries = $this->libraryEntryRepository->getNovelsByUser($user->getId());
        $entries = $this->serializer->serialize($entries, 'json', ['groups' => 'user-novel:get']);

        return new JsonResponse($entries, 200, [], true);
    }
}
