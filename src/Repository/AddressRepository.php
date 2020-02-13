<?php

namespace App\Repository;

use App\Entity\Address;
use App\Entity\AddressSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Address|null find($id, $lockMode = null, $lockVersion = null)
 * @method Address|null findOneBy(array $criteria, array $orderBy = null)
 * @method Address[]    findAll()
 * @method Address[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AddressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Address::class);
    }

    public function findAllQuery(AddressSearch $search)
    {
        $query = $this->createQueryBuilder('address');

        if ($search->getSearchInput()) {
            if ($search->getSearchBy()) {
                switch ($search->getSearchBy()) {
                    case 'name':
                        $query->andWhere('address.name LIKE :searchinput');
                        break;
                }
                $query->setParameter('searchinput', '%' . $search->getSearchInput() . '%');
            }
        }

        if ($search->getOrderBy()) {
            if ($search->getOrderBy() == "date") $search->setOrderBy("created_at");
            if ($search->getOrderDirection()) {
                $query
                    ->orderBy('address.' . $search->getOrderBy(), $search->getOrderDirection());
            }
        }

        return $query->getQuery();
    }

    // /**
    //  * @return Address[] Returns an array of Address objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Address
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
