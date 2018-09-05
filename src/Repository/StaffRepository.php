<?php

namespace App\Repository;

use App\Entity\Staff;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Staff|null find($id, $lockMode = null, $lockVersion = null)
 * @method Staff|null findOneBy(array $criteria, array $orderBy = null)
 * @method Staff[]    findAll()
 * @method Staff[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StaffRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Staff::class);
    }

    /**
     * @param User $owner
     * @return Staff[]
     */
    public function findByAllOwnerStaff(User $owner)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.owner = :owner')
            ->setParameter('owner', $owner)
            ->orderBy('s.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param User $owner
     * @param int $userId
     * @return Staff|null
     */
    public function findByOneOwnerStaff(User $owner, int $userId)
    {
        try {
            return $this->createQueryBuilder('s')
                ->andWhere('s.owner = :owner')
                ->andWhere('s.user = :user')
                ->setParameter('owner', $owner)
                ->setParameter('user', $userId)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            return null;
        }
    }

}
