<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\UserData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserData|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserData|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserData[]    findAll()
 * @method UserData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserDataRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserData::class);
    }

    /**
     * Query all records.
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->join('ud.user', 'u')
            ->orderBy('ud.date', 'DESC');
    }

    /**
     * Query datas by user.
     *
     * @param \App\Entity\User|null $user User entity
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    public function queryByUser(User $user = null): QueryBuilder
    {
        $queryBuilder = $this->queryAll();

        if (!is_null($user)) {
            $queryBuilder->andWhere('ud.user = :name')
                ->setParameter('name', $user);
        }

        return $queryBuilder;
    }

    /**
     * Get actual weight.
     *
     * @param \App\Entity\User|null $user User entity
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    public function getActualWeight(User $user = null): QueryBuilder
    {
        $queryBuilder = $this->queryAll();

        if (!is_null($user)) {
            $queryBuilder->andWhere('DATE(ud.date) <= CURRENT_DATE()')
                ->orderBy('ud.date', 'DESC')
                ->andWhere('ud.user = :name')
                    ->setParameter('name', $user);
            //->setMaxResults(1);
        }

//        $weight = $queryBuilder[0];

        return $queryBuilder;
    }

    /**
     * Get or create new query builder.
     *
     * @param \Doctrine\ORM\QueryBuilder|null $queryBuilder Query builder
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?: $this->createQueryBuilder('ud');
    }

    /**
     * Save record.
     *
     * @param \App\Entity\UserData $userData UserData entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(UserData $userData): void
    {
        $this->_em->persist($userData);
        $this->_em->flush($userData);
    }

    /**
     * Query datas by user.
     *
     * @param \App\Entity\User|null $user User entity
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    public function lastData(User $user = null): array
    {
        $queryBuilder = $this->queryAll();

        if (!is_null($user)) {
            $queryBuilder->andWhere('ud.user = :name')
                ->setParameter('name', $user)
            ->groupBy('DATE(ud.date)');
        }

        $queryBuilder->select('DATE(ud.date)', 'ud.weight');

        return $queryBuilder->getQuery()->getResult(Query::HYDRATE_ARRAY);
    }

    // /**
    //  * @return UserData[] Returns an array of UserData objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;->setMaxResults(1)
    }
    */

    /*
    public function findOneBySomeField($value): ?UserData
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
