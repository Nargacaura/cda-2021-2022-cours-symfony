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
        $latitude = $rq->query->get('latitude', 48.55596344379681);
        $longitude = $rq->query->get('longitude', 7.743205251587887);
        $radius = $rq->query->get('radius', 10);

        $annoncesInAddress = $addresses->findByPosition($latitude, $longitude, $radius);
        
        return $this->json($annoncesInAddress, 200, [], [
            'groups' => ['localisation', 'annonce']
        ]);
    }
}
