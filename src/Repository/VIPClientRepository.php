<?php

namespace App\Repository;

use App\Entity\VIPClient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method VIPClient|null find($id, $lockMode = null, $lockVersion = null)
 * @method VIPClient|null findOneBy(array $criteria, array $orderBy = null)
 * @method VIPClient[]    findAll()
 * @method VIPClient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VIPClientRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, VIPClient::class);
    }

//    /**
//     * @return VIPClient[] Returns an array of VIPClient objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?VIPClient
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
