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
        $query->innerJoin('c.products', 'p');

        if ($search->getState()) {
            foreach ($search->getState() as $k => $v) {
                $query->orWhere('c.state = :state' . $k)
                    ->setParameter('state' . $k, $v);
            }
        }


        /*
         * Pour filtrer avec le stock
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
        */

        if ($search->getSearchInput()) {
            if ($search->getSearchBy()) {
                switch ($search->getSearchBy()) {
                    case 'product':
                        $query->andWhere('p.reference LIKE :searchinput');
                        break;
                    case 'reference':
                        $query->andWhere('c.reference LIKE :searchinput');
                }
                $query->setParameter('searchinput', '%' . $search->getSearchInput() . '%');
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
