<?php
namespace App\Controller;

use App\Entity\Player;
use App\Service\PlayerGestion;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM\EntityManagerInterface;

class TournamentController extends AbstractController
{
    public function __construct(PlayerGestion $playerGestion)
    {
        $this->playerGestion = $playerGestion;
    }

    #[Route('/', name: 'index')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $player = new Player();

        $form = $this->createFormBuilder($player)
        ->add('pseudo', TextType::class, ['label' => 'Pseudo'])
        ->add('mdp', TextType::class, ['label' => 'Mot de passe'])
        ->add('email', TextType::class, ['label' => 'Email'])
        ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $pseudo = $player->getPseudo();
            $email = $player->getEmail();
            if ($this->playerGestion->playerExists($pseudo,$email)) {
                return new Response('Le pseudo existe déjà.');
            }

            $entityManager->persist($player);
            $entityManager->flush();
            return new Response('Données envoyées !');
        }
        return $this->render('base.html.twig', [
            'form' => $form,
        ]);
    }
}