<?php

namespace App\Repository;

use App\Entity\ExchangeOffice;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ExchangeOffice|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExchangeOffice|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExchangeOffice[]    findAll()
 * @method ExchangeOffice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExchangeOfficeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ExchangeOffice::class);
    }

    /**
     * @param int $id
     * @param User $owner
     * @return ExchangeOffice|null
     */
    public function findByOne(int $id, User $owner)
    {
        try {
            return $this->createQueryBuilder('e')
                ->andWhere('e.id = :id')
                ->andWhere('e.user = :owner')
                ->setParameter('id', $id)
                ->setParameter('owner', $owner)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            return null;
        }
    }
}
