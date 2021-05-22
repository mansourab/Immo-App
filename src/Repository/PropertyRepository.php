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
 
    private function getSearchQuery(SearchData $search) 
    {
        $query = $this
            ->createQueryBuilder('p')
            ->select('p')
            ->join('p.quarter', 'q')
        ;

        if (!empty($search->q)) {
            $query = $query  
                ->andWhere('q.name LIKE :q')
                ->setParameter('q', "%{$search->q}%")
            ;
        }

        return $query;
    }

}
