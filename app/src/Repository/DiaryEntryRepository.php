<?php

namespace App\Repository;

use App\Entity\DiaryEntry;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DiaryEntry::class);
    }

    /**
     * @param \App\Entity\User|null $user User entity
     *
     * @return QueryBuilder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
                ->join('de.product', 'p')
                ->join('de.meal', 'm')
                //->join('de.product', 'p')
                //->join('p.product_name', 'p')
                //->andWhere('YEAR(t.createdAt) = YEAR(NOW()) AND MONTH(t.createdAt) = MONTH(NOW()) AND DAY(t.createdAt) = (DAY(NOW())+1)')
                //->andWhere("DATE(de.date) >= DATE_SUB(CURRENT_DATE(), 1, 'day')")
                ->andWhere('DATE(de.date) = CURRENT_DATE()')
                //->groupBy('de.meal')
                ->orderBy('m.id');
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
            $queryBuilder->andWhere('de.user = :name')
                ->setParameter('name', $user);
        }

        return $queryBuilder;
    }

    /**
     * Query datas by user.
     *
     * @param \App\Entity\User|null $user User entity
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    public function queryByDate(User $user = null, $date): QueryBuilder
    {
        $queryBuilder = $this->getOrCreateQueryBuilder()
            ->join('de.product', 'p')
            ->join('de.meal', 'm')
            //->join('de.product', 'p')
            //->join('p.product_name', 'p')
            //->andWhere('YEAR(t.createdAt) = YEAR(NOW()) AND MONTH(t.createdAt) = MONTH(NOW()) AND DAY(t.createdAt) = (DAY(NOW())+1)')
            //->andWhere("DATE(de.date) >= DATE_SUB(CURRENT_DATE(), 1, 'day')")
            ->andWhere('DATE(de.date) = DATE($date)')
            //->groupBy('de.meal')
            ->orderBy('m.id');

        if (!is_null($user)) {
            $queryBuilder->andWhere('de.user = :name')
                ->setParameter('name', $user);
        }

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
        return $queryBuilder ?: $this->createQueryBuilder('de');
    }

    /**
     * Save record.
     *
     * @param \App\Entity\DiaryEntry $diaryEntry DiaryEntry entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(DiaryEntry $diaryEntry): void
    {
        $this->_em->persist($diaryEntry);
        $this->_em->flush($diaryEntry);
    }

    /**
     * Delete record.
     *
     * @param \App\Entity\DiaryEntry $diaryEntry DiaryEntry entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(DiaryEntry $diaryEntry): void
    {
        $this->_em->remove($diaryEntry);
        $this->_em->flush($diaryEntry);
    }

    // /**
    //  * @return DiaryEntry[] Returns an array of DiaryEntry objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DiaryEntry
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
