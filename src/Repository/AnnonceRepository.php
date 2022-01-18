<?php

namespace App\Repository;

use App\Entity\Annonce;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\AnnonceSearch;

/**
 * @method Annonce|null find($id, $lockMode = null, $lockVersion = null)
 * @method Annonce|null findOneBy(array $criteria, array $orderBy = null)
 * @method Annonce[]    findAll()
 * @method Annonce[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnonceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Annonce::class);
    }

    public function search(AnnonceSearch $annonceSearch)
    {
        $query = $this->createQueryBuilder('a');
        if ($annonceSearch->getCreatedAt() !== null) {
            $query
                ->andWhere('a.createdAt > :createdAt')
                ->setParameter(':createdAt', $annonceSearch->getCreatedAt())
            ;
        }
        
        if ($annonceSearch->getTitle() !== null) {
            $query
                ->andWhere('a.title LIKE :title')
                ->setParameter('title', '%'.$annonceSearch->getTitle().'%')
            ;
        }
    
        if ($annonceSearch->getStatus() !== null) {
            $query
                ->andWhere('a.status = :status')
                ->setParameter('status', $annonceSearch->getStatus())
            ;
        }
    
        if ($annonceSearch->getMaxPrice() !== null) {
            $query
                ->andWhere('a.price < :maxPrice')
                ->setParameter('maxPrice', $annonceSearch->getMaxPrice())
            ;
        }

        if ($annonceSearch->getTags()->count() > 0) {
            $cpt = 0;
            foreach ($annonceSearch->getTags() as $key => $tag) {
                $query = $query
                    ->andWhere(':tag'.$cpt.' MEMBER OF a.tags')
                    ->setParameter('tag'.$cpt, $tag);
                $cpt++;
            }
        }

        return $query->getQuery()->getResult();
    }

    // /**
    //  * @return Annonce[] Returns an array of Annonce objects
    //  */
    public function findAllNotSold(): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.sold = false')
            ->getQuery() // permet de créer un objet utilisable pour récupérer le résultat
            ->getResult() // permet de récupérer le résultat
        ;
    }

    public function findAllNotSoldQuery()
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.sold = false')
            ->getQuery() // permet de créer un objet utilisable pour récupérer le résultat
        ;
    }


    public function findLatestNotSold(): array
    {
        return $this->createQueryBuilder('a')
            ->setMaxResults(3)
            ->orderBy('a.id', 'desc')
            ->getQuery() // permet de créer un objet utilisable pour récupérer le résultat
            ->getResult() // permet de récupérer le résultat
        ;
    }

    public function fetch(array $filters)
    {
        // on créer QueryBuilder qui va nous permettre d'écrire une requête
        $results = $this->createQueryBuilder('a');
        if(isset($filters["better_than"])) {
            $results->andWhere('a.status >= :status')->setParameter('status', $filters["better_than"]);
        }

        if(isset($filters["newer_than"])) {
            $results->andWhere('a.createdAt >= :createdAt')->setParameter('createdAt', $filters["newer_than"]);
        }            

        return $results->orderBy("a.createdAt", 'desc')->getQuery()->getResult();
    }
}
