<?php

namespace App\Controller\Profile;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnnonceController extends AbstractController
{
    #[Route('/profile/annonce', name: 'profile_annonce')]
    public function index(): Response
    {
        $user = dump($this->getUser());
        return $this->render('profile/annonce/index.html.twig', [
            'prenom' => $user->getFirstname(),
            'nom' => $user->getLastname(),
            'pseudo' => $user->getPseudonym(),
            'annonces' => $user->getAnnonces(), // récupère toutes les annonces de l'utilisateur
        ]);
    }
}
