<?php

namespace App\Repository;

use App\Entity\ExchangeOffice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
}
