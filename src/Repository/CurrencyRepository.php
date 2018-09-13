<?php

namespace App\Repository;

use App\Entity\Currency;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Currency|null find($id, $lockMode = null, $lockVersion = null)
 * @method Currency|null findOneBy(array $criteria, array $orderBy = null)
 * @method Currency[]    findAll()
 * @method Currency[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CurrencyRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Currency::class);
    }

    /**
     * @param string $iso
     * @return Currency | null
     */
    public function findByOneIso($iso)
    {
        try {
            return $this->createQueryBuilder('c')
                ->where('c.iso = :iso')
                ->setParameter('iso', $iso)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            return null;
        }
    }

    /**
     * @param int $userId
     * @return array|null
     */
    public function findByCurrency(int $userId)
    {
        try {
            $conn = $this->getEntityManager()->getConnection();
            $sql = 'SELECT currency.* FROM cashbox
                inner join currency on cashbox.currency_id = currency.id
                where cashbox.user_id = :id
                group by cashbox.currency_id';

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':id' => $userId
            ]);

            return $stmt->fetchAll();

        } catch (DBALException $e) {
            return null;
        }
    }

    public function findAllExcept($ids)
    {
        if (empty($ids)) {
            return $this->findAll();
        }

        $qb = $this->createQueryBuilder('c');

        return $qb->andWhere($qb->expr()->notIn('c.id', $ids))
            ->getQuery()
            ->getResult();
    }

    public function findAllByOwner(User $owner)
    {
        return $this->createQueryBuilder('c')
            ->join('c.cashboxes', 'b')
            ->join('b.exchangeOffice', 'e')
            ->andWhere('e.user = :owner')
            ->setParameter('owner', $owner)
            ->getQuery()
            ->getResult();
    }


}
