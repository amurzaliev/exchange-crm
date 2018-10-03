<?php

namespace App\Repository;

use App\Entity\Cashbox;
use App\Entity\ExchangeOffice;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
    public function findByOne(int $id, User $owner = null)
    {
        try {
            $qb = $this->createQueryBuilder('e')
                ->andWhere('e.id = :id')
                ->setParameter('id', $id)
            ;

            if ($owner) {
                $qb
                    ->andWhere('e.user = :owner')
                    ->setParameter('owner', $owner)
                ;
            }


            return $qb
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            return null;
        }
    }

    /**
     * @param User $owner
     * @return ExchangeOffice[]|null
     */
    public function findByAllOwnersExchange(User $owner)
    {
        try {
            return $this->createQueryBuilder('e')
                ->andWhere('e.user = :owner')
                ->setParameter('owner', $owner)
                ->getQuery()
                ->getArrayResult();
        } catch (NotFoundHttpException $e) {
            return null;
        }
    }

    public function findAllByOwner(User $owner)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.user = :owner')
            ->setParameter('owner', $owner)
            ->getQuery()
            ->getResult();
    }


    /**
     * @param $exchangeId
     * @return ExchangeOffice[]|null
     */
    public function findByAllExchange($exchangeId)
    {
        try {
            return $this->createQueryBuilder('e')
                ->where("e.id IN(:exchangeId)")
                ->setParameter('exchangeId', $exchangeId)
                ->getQuery()
                ->getArrayResult();
        } catch (NotFoundHttpException $e) {
            return null;
        }
    }

    /**
     * @param null $owner
     * @return ExchangeOffice[]|null
     */
    public function findAllByOwner($owner = null)
    {
        try {
            $qb =  $this->createQueryBuilder('e');

            if ($owner) {
                $qb
                    ->andWhere('e.user = :owner')
                    ->setParameter('owner', $owner)
                ;
            }

            return $qb
                ->orderBy("e.id", "desc")
                ->getQuery()
                ->getResult()
                ;
        } catch (NotFoundHttpException $e) {
            return null;
        }
     }


    /**
     * @param User $owner
     * @return ExchangeOffice[]|null
     */
    public function findByAllOwnersExchangesResult (User $owner)
    {
        try {
            return $this->createQueryBuilder('e')->select()
                ->addSelect("cashboxes")
                ->addSelect("currencyRates")
                ->addSelect("currency")
                ->join("e.cashboxes", "cashboxes")
                ->join("cashboxes.currency", "currency")
                ->innerJoin("cashboxes.currencyRates", "currencyRates")
                ->andWhere('e.user = :owner')
                ->orderBy('currency.id', 'ASC')
                ->addOrderBy("currencyRates.id", "DESC")
                ->setParameter('owner', $owner)
                ->getQuery()
                ->getResult();

        } catch (NotFoundHttpException $e) {
            return null;
        }
    }

    public function getIdArrayExchangeOffice(User $owner)
    {
        $rows = $this->findByAllOwnersExchangesResult($owner);
        $result = [];

        if ($rows) {
            foreach ($rows as $row) {
                $result[] = $row->getId();
            }
        }

        return $result;
    }

    public function getCurrencyRateExchangeOffice(User $owner, $currencys)
    {

        $currencysId = [];
        foreach ($currencys as $currency) {
            $currencysId[] = $currency['id'];
        }

        $exchangeOffices = $this->findByAllOwnersExchangesResult($owner);
        for ($i = 0; $i < count($exchangeOffices); $i++) {

            $cashboxAndCurrencyIdList = [];

            /** @var Cashbox $cashbox */
            foreach ($exchangeOffices[$i]->getCashboxes() as $cashbox) {
                $cashboxAndCurrencyIdList['currencyId'][] = $cashbox->getCurrency()->getId();
                $cashboxAndCurrencyIdList['cashbox'][$cashbox->getCurrency()->getId()] = $cashbox;
            }

            /** @var Cashbox[] $cashboxs */
            $cashboxs = [];
            foreach ($currencysId as $id) {
                if (in_array($id, $cashboxAndCurrencyIdList['currencyId'])) {
                    $cashboxs[] = $cashboxAndCurrencyIdList['cashbox'][$id];
                } else {
                    $cashboxs[] = null;
                }
            }
            $exchangeOffices[$i]->cashboxs = $cashboxs;
        }

        return $exchangeOffices;
    }
}