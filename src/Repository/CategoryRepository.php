<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function deleteAllCategories()
    {
        return $this->createQueryBuilder('c')
            ->delete()
            ->getQuery()->execute();
    }


    /**
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findLastProduct()
    {
        $lastCategory = $this->createQueryBuilder('c')
            ->orderBy('c.createdAt', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if ($lastCategory === null) {
            throw new NonUniqueResultException('Не найдено категорий');
        }

        return $lastCategory;
    }

    /**
     * @param string|null $term
     * @return mixed
     */
    public function findCategoriesWithSearch(?string $term)
    {
        $qb = $this->createQueryBuilder('c');

        if ($term !== null) {
            $qb->andWhere('c.name LIKE :term')
                ->setParameter('term' , '%' . $term . '%');
        }

        return $qb->orderBy('c.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Category[] Returns an array of Category objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Category
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
