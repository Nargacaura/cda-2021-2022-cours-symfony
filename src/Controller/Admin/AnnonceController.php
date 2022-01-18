<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AnnonceRepository;
use App\Entity\Annonce;
use App\Form\AnnonceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin")
 */
class AnnonceController extends AbstractController
{
    public function adminDashboard()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');
    }

     /**
     * @Route("/annonce", name="admin")
     */
    public function index(AnnonceRepository $annonceRepository)
    {
        $ducks = $annonceRepository->findAll();

        return $this->render('admin/annonce/index.html.twig', [
            'ducks' => $ducks,
        ]);
    }

    

}
