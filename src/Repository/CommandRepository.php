<?php

namespace App\Repository;

use App\Entity\Command;
use App\Entity\CommandSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Command|null find($id, $lockMode = null, $lockVersion = null)
 * @method Command|null findOneBy(array $criteria, array $orderBy = null)
 * @method Command[]    findAll()
 * @method Command[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Command::class);
    }

    /**
     * @param CommandSearch $search
     * @return \Doctrine\ORM\Query Returns an array of Command objects
     */
    public function findAllQuery(CommandSearch $search)
    {
        $query = $this->createQueryBuilder('c');

        if ($search->getState()) {
            foreach ($search->getState() as $k => $v) {
                $query->orWhere('c.state = :state' . $k)
                    ->setParameter('state' . $k, $v);
            }
        }


        if ($search->getStock()) {
            foreach ($search->getStock() as $k => $v) {
                dump($v);
                /*
                //Pour filtrer avec le stock
                $query->andWhere($query->expr()->orX(
                    $query->expr()->eq('c.quantity', '5000')
                ));
                */
            }
        }


        if ($search->getSearchBy()) {
            if ($search->getSearchInput()) {
                $query
                    ->andWhere('c.' . $search->getSearchBy() . ' LIKE :searchinput')
                    ->setParameter('searchinput', '%' . $search->getSearchInput() . '%');
            }
        }

        return $query->getQuery();
    }


    /*
    public function findOneBySomeField($value): ?Command
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
