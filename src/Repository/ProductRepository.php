<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\ProductSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @param ProductSearch $search
     * @return \Doctrine\ORM\Query
     */
    public function findAllQuery(ProductSearch $search)
    {
        $query = $this->createQueryBuilder('p');

        if ($search->getStock()) {
            $filters = array();
            foreach ($search->getStock() as $k => $v) {
                switch ($v) {
                    case "empty":
                        array_push($filters, 'p.quantity = 0');
                        break;
                    case "low":
                        array_push($filters, 'p.quantity <= p.critical AND p.quantity > 0');
                        break;
                    case "high":
                        array_push($filters, 'p.quantity > p.critical');
                        break;
                }
            }
            $orX = $query->expr()->orX();
            $orX->addMultiple($filters);
            $query->andWhere($orX);
        }

        if ($search->getSearchInput()) {
            $query
                ->andWhere('p.reference' . ' LIKE :searchinput')
                ->setParameter('searchinput', '%' . $search->getSearchInput() . '%');
        }

        if ($search->getOrderBy()) {
            if ($search->getOrderDirection()) {
                $query
                    ->orderBy('p.' . $search->getOrderBy(), $search->getOrderDirection());
            }
        }

        return $query->getQuery();
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
