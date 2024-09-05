<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Player;

class PlayerGestion
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function playerExists($pseudo, $email): bool
    {
        $repository = $this->entityManager->getRepository(Player::class);
       
        // Vérifier si le pseudo ou le mail existe déjà
         $playerByPseudo = $repository->findOneBy(['pseudo' => $pseudo]);
         $playerByMail = $repository->findOneBy(['email' => $email]);
 
        // Retourner true si l'un des deux existe
         return $playerByPseudo !== null || $playerByMail !== null;
    }

}