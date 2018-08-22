<?php

namespace App\Repository;

use App\Entity\Cashbox;
use App\Entity\ExchangeOffice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

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

    /**
     * @param ExchangeOffice $exchangeOffice
     * @return array|null
     */
    public function findByAll(ExchangeOffice $exchangeOffice)
    {
        try {
            $conn = $this->getEntityManager()->getConnection();

            $sql = '
                SELECT 
                    c.*,
                    cu.name,
                    cu.icon,
                    cu.default_currency,
                    cu.iso,
                    cu.decimals,
                    cu.decimal_separator,
                    cu.thousand_separator,
                    (
                        SELECT 
                            IF(SUM(t.amount), SUM(t.amount),0)  
                        FROM 
                            transactions t  
                        WHERE 
                            t.сashbox_to_id = c.id
                            AND 
                            t.basic_type = 1
                    ) - (
                        SELECT 
                            IF(SUM(t.amount), SUM(t.amount),0) 
                        FROM 
                            transactions t  
                        WHERE 
                            t.сashbox_from_id = c.id
                            AND 
                            t.basic_type = 2
                    ) as summa,
                    (
                        SELECT  r.purchase FROM currency_rate r 
                        WHERE r.cashbox_currency_id = c.id
                        ORDER BY created_at DESC LIMIT 1
                    ) as purchase,
                    (
                        SELECT  r.sale FROM currency_rate r 
                        WHERE r.cashbox_currency_id = c.id
                        ORDER BY created_at DESC LIMIT 1
                    ) as sale
                FROM 
                    cashbox c
                INNER JOIN currency cu ON cu.id = c.currency_id
                WHERE c.exchange_office_id = :exchange_office_id
            ';
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':exchange_office_id' => $exchangeOffice->getId()
            ]);

            return $stmt->fetchAll();

        } catch (DBALException $e) {
            return null;
        }
    }

    /**
     * @param int $id
     * @param ExchangeOffice $exchangeOffice
     * @return Cashbox|null
     */
    public function findByOne(int $id, ExchangeOffice $exchangeOffice)
    {
        try {
            return $this->createQueryBuilder('c')
                ->andWhere('c.id = :id')
                ->andWhere('c.exchangeOffice = :exchangeOffice')
                ->setParameter("id", $id)
                ->setParameter("exchangeOffice", $exchangeOffice)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            return null;
        }
    }

    /**
     * @param ExchangeOffice $exchangeOffice
     * @return Cashbox
     */
    public function findByOneDefaultCurrency(ExchangeOffice $exchangeOffice)
    {
        try {
            /** @var Cashbox $cashbox */
            return $this->createQueryBuilder('c')
                ->andWhere('c.exchangeOffice = :exchangeOffice')
                ->join('c.currency', 'cu', 'WITH', 'cu.defaultCurrency = :defaultCurrency')
                ->setParameter("exchangeOffice", $exchangeOffice)
                ->setParameter("defaultCurrency", true)
                ->getQuery()
                ->getOneOrNullResult()
            ;
        } catch (NonUniqueResultException $e) {
            return null;
        }
    }


    /**
     * @param string $currency
     * @return Cashbox|null
     */
    public function findByCurrencyName( string $currency)
    {
        try {
            return $this->createQueryBuilder('c')
                ->andWhere('c.currency = :currency')
                ->setParameter("exchangeOffice", $currency)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            return null;
        }
    }


    public function getAllAmount(int $id, ExchangeOffice $exchangeOffice)
    {
        try {
            $conn = $this->getEntityManager()->getConnection();

            $sql = '
                SELECT 
                 
                    (
                        SELECT 
                            IF(SUM(t.amount), SUM(t.amount),0)  
                        FROM 
                            transactions t  
                        WHERE 
                            t.сashbox_to_id = c.id
                            AND 
                            t.basic_type = 1
                    ) - (
                        SELECT 
                            IF(SUM(t.amount), SUM(t.amount),0) 
                        FROM 
                            transactions t  
                        WHERE 
                            t.сashbox_from_id = c.id
                            AND 
                            t.basic_type = 2
                    ) as summa
                FROM 
                    cashbox c
                 WHERE 
                    c.id = :id 
                 AND 
                    c.exchange_office_id = :exchange_office_id
            ';
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':id' => $id,
                ':exchange_office_id' => $exchangeOffice->getId(),
            ]);

            return $stmt->fetchAll();

        } catch (DBALException $e) {
            return null;
        }
    }

    /**
     * @param ExchangeOffice $exchangeOffice
     * @return Cashbox[] | null
     */
    public function findByExchangeOffice($exchangeOffice)
    {
        try {
            return $this->createQueryBuilder('c')
                ->andWhere('c.exchangeOffice = :exchangeOffice')
                ->setParameter('exchangeOffice', $exchangeOffice)
                ->orderBy('c.createdAt', 'DESC')
                ->getQuery()
                ->getResult();
        } catch (NotFoundResourceException $e) {
            return null;
        }

    }

    /**
     * @param int $exchangeOfficeId
     * @return Cashbox | null
     */
    public function findByCashboxes( int $exchangeOfficeId)
    {
        try {
            return $this->createQueryBuilder('c')
                ->andWhere('c.exchangeOffice = :exchangeOffice')
                ->setParameter("exchangeOffice", $exchangeOfficeId)
                ->getQuery()
                ->getResult();
        } catch (NotFoundResourceException $e) {
            return null;
        }



    }
}
