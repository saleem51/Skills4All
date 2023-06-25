<?php

namespace App\Repository;

use App\data\SearchData;
use App\Entity\Car;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Car>
 *
 * @method Car|null find($id, $lockMode = null, $lockVersion = null)
 * @method Car|null findOneBy(array $criteria, array $orderBy = null)
 * @method Car[]    findAll()
 * @method Car[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, protected PaginatorInterface $paginator)
    {
        parent::__construct($registry, Car::class);
    }

    public function save(Car $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Car $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //requête queryBuilder pour récupérer les donnée avec la pagination
    public function findPagination(int $page): PaginationInterface
    {
        $query = $this->createQueryBuilder("p")
                      ->select('p');

        $query = $query->getQuery();

        return $this->paginator->paginate(
            $query,
            $page,
            10
        );
    }

    /**
     * @return Car[]
     */
    //requête queryBuilder pour faire les recherches avec les filtres
    public function findSearch(SearchData $search): PaginationInterface
    {
        $query = $this->createQueryBuilder('p')
                      ->select(  'p');
                      //->join('p.categories', 'c');



        if(!empty($search->search))
        {
            $query = $query->andWhere('p.name LIKE :search')
                           ->setParameter('search', "%{$search->search}%");
        }

        if(!empty($search->categories))
        {
            $query = $query->andWhere('p.category IN (:categories)')
                           ->setParameter('categories', $search->categories);
        }

        $query =  $query->getQuery();

        return $this->paginator->paginate(
            $query,
            $search->page,
            15
        );
    }
}