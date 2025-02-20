<?php

namespace App\Controller;

use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AnnonceRepository;
use App\Repository\TagRepository;

class HomeController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index(AnnonceRepository $annonceRepository, TagRepository $tagRepo): Response
    {
        $ducks = $annonceRepository->findLatestNotSold();
        $tags = $tagRepo->findAll();

        // affiche le fichier index.html.twig
        return $this->render('home/index.html.twig', [
            'date' => new DateTime(),
            'content' => 'Lorem ipsum...',
            'title' => 'Bienvenue sur le MetaVerse !',
            'ducks' => $ducks,
            'tags' => $tags
        ]);
    }
}