<?php
/**
 * Product repository.
 */

namespace App\Repository;

use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * Class ProductRepository.
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    /**
     * ProductRepository constructor.
     *
     * @param RegistryInterface $registry Registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * Query all records.
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->join('p.category', 'c')
            ->join('p.user', 'u')
            ->orderBy('p.id', 'DESC');
    }

    /**
     * Query products by category.
     *
     * @param int $categoryId
     *
     * @return QueryBuilder Query builder
     */
    public function queryByCategory(int $categoryId): QueryBuilder
    {
        $queryBuilder = $this->queryAll();
        $queryBuilder->andWhere('p.category = :category')
                ->setParameter('category', $categoryId);

        return $queryBuilder;
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
            $queryBuilder->andWhere('p.user = :name')
            ->setParameter('name', $user)
                ->orWhere('p.isAccepted = 1');
        }

        return $queryBuilder;
    }

    /**
     * Query products by category and user.
     *
     * @param int       $categoryId
     * @param User|null $user       User entity
     *
     * @return QueryBuilder Query builder
     */
    public function queryByCategoryAndUser(int $categoryId, User $user = null): QueryBuilder
    {
        $queryBuilder = $this->queryAll();
        $queryBuilder
            ->andWhere('p.user = :name')
            ->setParameter('name', $user)
            ->orWhere('p.isAccepted = 1')
            ->andWhere('p.category = :category')
            ->setParameter('category', $categoryId);

        return $queryBuilder;
    }

    /**
     * Save record.
     *
     * @param Product $product Product entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Product $product): void
    {
        $this->_em->persist($product);
        $this->_em->flush($product);
    }

    /**
     * Delete record.
     *
     * @param Product $product Product entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Product $product): void
    {
        $this->_em->remove($product);
        $this->_em->flush($product);
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
        return $queryBuilder ?: $this->createQueryBuilder('p');
    }
}
