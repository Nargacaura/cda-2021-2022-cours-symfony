<?php

namespace App\Controller\Api;

use App\Repository\AddressRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnnonceController extends AbstractController
{
    #[Route('/api/annonce/search-by-position')]
    public function searchByPosition(Request $rq, AddressRepository $addresses): Response
    {
        $latitude = $rq->query->get('latitude');
        $longitude = $rq->query->get('longitude');
        $radius = $rq->query->get('radius', 10);

        $annoncesInAddress = $addresses->findByPosition($latitude, $longitude, $radius);
        dd($annoncesInAddress);
    }
}
