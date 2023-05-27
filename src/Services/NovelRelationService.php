<?php 

namespace App\Services;

use App\Entity\Novel;
use App\Entity\User;
use App\Repository\UserNovelRepository;

class NovelRelationService {

    public function __construct(UserNovelRepository $userNovelRepository)
    {
        $this->userNovelRepository = $userNovelRepository;
    }
    
    public function isUserAuthorized(Novel $novel = null, User $user = null, array $authorizedRelations = array('author'))
    {
        if ($novel === null || $user === null) {
            return false;
        }
        $user_novel = $this->userNovelRepository->findOneBy(['novel' => $novel->getId(), 'user' => $user->getId()]);

        if (!$user_novel || !in_array($user_novel->getRelation(),  $authorizedRelations)) {
            return false;
        }

        return true;
    }
}