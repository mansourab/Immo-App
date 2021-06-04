<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Property;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
            3
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
            ->select('p', 'c', 'l')
            ->join('p.categories', 'c')
            ->join('p.quarter', 'l')

        ;


        if (!empty($search->q)) {
            $query = $query  
                ->andWhere('p.title LIKE :q')
                ->setParameter('q', "%{$search->q}%")
            ;
        }


        if (!empty($search->quarter)) {
            $query = $query  
                ->andWhere('l.name LIKE :q')
                ->setParameter('q', "%{$search->quarter}%")
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

        return $query;
    }

}
