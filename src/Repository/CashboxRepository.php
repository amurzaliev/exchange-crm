<?php

namespace App\Repository;

use App\Entity\Cashbox;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Cashbox|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cashbox|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cashbox[]    findAll()
 * @method Cashbox[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CashboxRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Cashbox::class);
    }

//    /**
//     * @return Cashbox[] Returns an array of Cashbox objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Cashbox
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
