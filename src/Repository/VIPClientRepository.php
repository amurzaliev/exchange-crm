<?php

namespace App\Repository;

use App\Entity\VIPClient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
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

    /**
     * @param $owner
     * @return VIPClient[] Returns an array of VIPClient objects
     */
    public function findByOwner($owner)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.user = :owner')
            ->setParameter('owner', $owner)
            ->orderBy('v.id', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param int $id
     * @param $owner
     * @return VIPClient|null
     */
    public function findByOneOwner(int $id, $owner)
    {
        try {
            return $this->createQueryBuilder('v')
                ->andWhere('v.id = :id')
                ->andWhere('v.user = :owner')
                ->setParameter('id', $id)
                ->setParameter('owner', $owner)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            return null;
        }
    }
}
