<?php
/**
 * UserData repository.
 */

namespace App\Repository;

use App\Entity\User;
use App\Entity\UserData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class UserDataRepository.
 *
 * @method UserData|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserData|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserData[]    findAll()
 * @method UserData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserDataRepository extends ServiceEntityRepository
{
    /**
     * UserDataRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserData::class);
    }

    /**
     * Query all records.
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->join('ud.user', 'u')
            ->orderBy('ud.date', 'DESC');
    }

    /**
     * Query data by user.
     *
     * @param User|null $user User entity
     *
     * @return QueryBuilder Query builder
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
     * @param User|null $user User entity
     *
     * @return QueryBuilder Query builder
     */
    public function getActualWeight(User $user = null): QueryBuilder
    {
        $queryBuilder = $this->queryAll();

        if (!is_null($user)) {
            $queryBuilder->andWhere('DATE(ud.date) <= CURRENT_DATE()')
                ->orderBy('ud.date', 'DESC')
                ->andWhere('ud.user = :name')
                    ->setParameter('name', $user);
        }

        return $queryBuilder;
    }

    /**
     * Save record.
     *
     * @param UserData $userData UserData entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(UserData $userData): void
    {
        $this->_em->persist($userData);
        $this->_em->flush($userData);
    }

    /**
     * Query data by user.
     *
     * @param User|null $user User entity
     *
     * @return array Query builder
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

    /**
     * Get or create new query builder.
     *
     * @param QueryBuilder|null $queryBuilder Query builder
     *
     * @return QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?: $this->createQueryBuilder('ud');
    }
}
