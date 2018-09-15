<?php

namespace App\Repository;

use App\Entity\Transactions;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Transactions|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transactions|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transactions[]    findAll()
 * @method Transactions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Transactions::class);
    }

    public function getAllTransactionsByFilter(User $owner, array $filter)
    {
        if (empty($filter['exchangeOffices'])) {
            return null;
        }

        $qb = $this->createQueryBuilder('t');

        $qb
            ->join('t.exchangeOffice', 'e')
            ->andWhere('e.user = :owner')
            ->setParameter('owner', $owner)
            ->andWhere($qb->expr()->in('t.exchangeOffice', $filter['exchangeOffices']));

        if (!empty($filter['currencies'])) {
            $qb
                ->leftJoin('t.cashboxFrom', 'cf')
                ->leftJoin('t.cashboxTo', 'ct')
                ->andWhere(
                    $qb->expr()->in('cf.currency', $filter['currencies']) . ' OR ' .
                    $qb->expr()->in('ct.currency', $filter['currencies'])
                );
        }

        if (!empty($filter['transactionTypes']) && count($filter['transactionTypes']) === 1) {

            switch ($filter['transactionTypes'][0]) {
                case 'cashboxTo':
                    $qb->andWhere($qb->expr()->isNull('t.cashboxFrom'));
                    break;
                case 'cashboxFrom':
                    $qb->andWhere($qb->expr()->isNull('t.cashboxTo'));
                    break;
            }

        }

        if (!empty($filter['operators'])) {
            $qb->andWhere($qb->expr()->in('t.user', $filter['operators']));
        }

        if (!empty($filter['dateFrom'])) {
            $qb
                ->andWhere('t.createdAt >= :dateFrom')
                ->setParameter('dateFrom', \DateTime::createFromFormat('d/m/Y', $filter['dateFrom']));
        }

        if (!empty($filter['dateTo'])) {
            $qb
                ->andWhere('t.createdAt <= :dateTo')
                ->setParameter('dateTo', \DateTime::createFromFormat('d/m/Y', $filter['dateTo']));
        }

        $maxResult = $filter['maxResult'] ?? 15;

        return $qb
            ->orderBy('t.updateAt', 'DESC')
            ->getQuery()
            ->setMaxResults($maxResult)
            ->getResult();
    }
}
