<?php
/**
 * DiaryEntry repository.
 */

namespace App\Repository;

use App\Entity\DiaryEntry;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DiaryEntry|null find($id, $lockMode = null, $lockVersion = null)
 * @method DiaryEntry|null findOneBy(array $criteria, array $orderBy = null)
 * @method DiaryEntry[]    findAll()
 * @method DiaryEntry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DiaryEntryRepository extends ServiceEntityRepository
{
    /**
     * DiaryEntryRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DiaryEntry::class);
    }

    /**
     * @return QueryBuilder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
                ->join('de.product', 'p')
                ->join('de.meal', 'm')
                ->andWhere('DATE(de.date) = CURRENT_DATE()')
                ->orderBy('m.id');
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
            $queryBuilder->andWhere('de.user = :name')
                ->setParameter('name', $user);
        }

        return $queryBuilder;
    }

    /**
     * Query data by past date.
     *
     * @param User|null $user User entity
     *
     * @param int       $sub
     *
     * @return QueryBuilder Query builder
     */
    public function queryByPastDate(User $user = null, int $sub): QueryBuilder
    {
        $queryBuilder = $this->getOrCreateQueryBuilder()
                ->andWhere('de.user = :name')
                ->setParameter('name', $user);

        $queryBuilder
            ->join('de.product', 'p')
            ->join('de.meal', 'm')
            ->andWhere("DATE(de.date) = DATE_SUB(CURRENT_DATE(), $sub, 'day')")
            ->orderBy('m.id');

        return $queryBuilder;
    }

    /**
     * Save record.
     *
     * @param DiaryEntry $diaryEntry DiaryEntry entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(DiaryEntry $diaryEntry): void
    {
        $this->_em->persist($diaryEntry);
        $this->_em->flush($diaryEntry);
    }

    /**
     * Delete record.
     *
     * @param DiaryEntry $diaryEntry DiaryEntry entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(DiaryEntry $diaryEntry): void
    {
        $this->_em->remove($diaryEntry);
        $this->_em->flush($diaryEntry);
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
        return $queryBuilder ?: $this->createQueryBuilder('de');
    }
}
