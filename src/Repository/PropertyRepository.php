<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Property;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Property|null find($id, $lockMode = null, $lockVersion = null)
 * @method Property|null findOneBy(array $criteria, array $orderBy = null)
 * @method Property[]    findAll()
 * @method Property[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropertyRepository extends ServiceEntityRepository
{

    private $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Property::class);
        $this->paginator = $paginator;
    }

    /**
     * @return PaginationInterface
     */
    public function findSearch(SearchData $search): PaginationInterface
    {
        $query = $this->getSearchQuery($search)->getQuery();

        return $this->paginator->paginate(
            $query,
            $search->page,
            15
        );
    }

    public function findMinMax(SearchData $search): array
    {
        $results = $this->getSearchQuery($search, true)
            ->select('MIN(p.price) as min', 'MAX(p.price) as max')
            ->getQuery()
            ->getScalarResult()
        ;

        return [(int)$results[0]['min'], (int)$results[0]['max']];
    }
 
    private function getSearchQuery(SearchData $search, $ignorePrice = false) 
    {
        $query = $this
            ->createQueryBuilder('p')
            ->select('p', 'c', 'l', 't')
            ->join('p.categories', 'c')
            ->join('p.quarter', 'l')
            ->join('p.types', 't')
            ->orderBy('p.createdAt', 'DESC')
        ;


        if (!empty($search->q)) {
            $query = $query  
                ->andWhere('l.name LIKE :q')
                ->setParameter('q', "%{$search->q}%")
            ;
        } 


        if (!empty($search->min) && $ignorePrice === false) {
            $query = $query  
                ->andWhere('p.price >= :min')
                ->setParameter('min', $search->min)
            ;
        }


        if (!empty($search->max) && $ignorePrice === false) {
            $query = $query  
                ->andWhere('p.price <= :max')
                ->setParameter('max', $search->max)
            ;
        }

        if (!empty($search->categories)) {
            $query = $query  
                ->andWhere('c.id IN (:categories)')
                ->setParameter('categories', $search->categories)
            ;
        }

        if (!empty($search->types)) {
            $query = $query  
                ->andWhere('t.id IN (:types)')
                ->setParameter('types', $search->types)
            ;
        }

        return $query;
    }

    /**
     * Les derniers biens
     */
    public function FindLatestProperties()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults(8)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Les derniers biens en location
     */
    public function findLatestRent()
    {
        return $this->createQueryBuilder('p')
            ->join('p.types', 't')
            ->andWhere("t.name = 'A Louer'")
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults(8)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Les derniers biens en vente
     */
    public function findLatestSale()
    {
        return $this->createQueryBuilder('p')
            ->join('p.types', 's')
            ->andWhere("s.name = 'A Vendre'")
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults(8)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Les biens mis en avant
     */
    public function findFeatured()
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.featured = 1')
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults(6)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Les annonces Terminées
     */
    public function findActif()
    {
        return $this->createQueryBuilder('p')
            ->andWhere("p.status = 'Actif'")
            ->andWhere('p.published = 1')
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Les annonces Annuler
     */
    public function findAnnuler()
    {
        return $this->createQueryBuilder('p')
            ->andWhere("p.status = 'Annuler'")
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults(6)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Les annonces Terminées
     */
    public function findTerminer()
    {
        return $this->createQueryBuilder('p')
            ->andWhere("p.status = 'Terminer'")
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults(6)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Les annonces Non Publiées (Inactives)
     */
    public function findInactive()
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.published = 0')
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults(6)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Pour la page a-propos
     */
    public function numberSale()
    {
        return $this->createQueryBuilder('p')
            ->join('p.types', 's')
            ->andWhere("s.name = 'A Vendre'")
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Pour la page a-propos
     */
    public function numberRent()
    {
        return $this->createQueryBuilder('p')
            ->join('p.types', 's')
            ->andWhere("s.name = 'A Louer'")
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Numbre d'annonces dans la ville de niamey
     */
    public function findNiamey()
    {
        return $this->createQueryBuilder('p')
            ->join('p.state', 's')
            ->andWhere("s.name = 'Niamey'")
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Numbre d'annonces dans la ville de Dosso
     */
    public function findDosso()
    {
        return $this->createQueryBuilder('p')
            ->join('p.state', 's')
            ->andWhere("s.name = 'Dosso'")
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Numbre d'annonces dans la ville de Maradi
     */
    public function findMaradi()
    {
        return $this->createQueryBuilder('p')
            ->join('p.state', 's')
            ->andWhere("s.name = 'Maradi'")
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Numbre d'annonces dans la ville de Zinder
     */
    public function findZinder()
    {
        return $this->createQueryBuilder('p')
            ->join('p.state', 's')
            ->andWhere("s.name = 'Zinder'")
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Numbre d'annonces dans la ville de Agadez
     */
    public function findAgadez()
    {
        return $this->createQueryBuilder('p')
            ->join('p.state', 's')
            ->andWhere("s.name = 'Agadez'")
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Numbre d'annonces dans la ville de Tillaberi
     */
    public function findTillaberi()
    {
        return $this->createQueryBuilder('p')
            ->join('p.state', 's')
            ->andWhere("s.name = 'Tillaberi'")
            ->getQuery()
            ->getResult()
        ;
    }


    /**
     * Numbre d'annonces dans la ville de Tahoua
     */
    public function findTahoua()
    {
        return $this->createQueryBuilder('p')
            ->join('p.state', 's')
            ->andWhere("s.name = 'Tahoua'")
            ->getQuery()
            ->getResult()
        ;
    }


    /**
     * Numbre d'annonces dans la ville de Diffa
     */
    public function findDiffa()
    {
        return $this->createQueryBuilder('p')
            ->join('p.state', 's')
            ->andWhere("s.name = 'Diffa'")
            ->getQuery()
            ->getResult()
        ;
    }

}
