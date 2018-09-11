<?php

namespace App\Repository;

use App\Entity\PermissionGroup;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PermissionGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method PermissionGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method PermissionGroup[]    findAll()
 * @method PermissionGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PermissionGroupRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PermissionGroup::class);
    }

    public function findAllByOwner(User $owner)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.user = :owner')
            ->setParameter('owner', $owner)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return PermissionGroup[] Returns an array of PermissionGroup objects
//     */
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
    public function findOneBySomeField($value): ?PermissionGroup
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
