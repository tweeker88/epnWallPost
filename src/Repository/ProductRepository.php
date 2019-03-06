<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findLastProduct()
    {
        $lastProduct = $this->createQueryBuilder('p')
            ->orderBy('p.createdAt', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if ($lastProduct === null) {
            throw new NonUniqueResultException('Не найдено товаров');
        }

        return $lastProduct;
    }


    public function findIdProducts(): array
    {
        return $this->createQueryBuilder('p')
            ->select('p.productId')
            ->getQuery()
            ->getArrayResult();
    }

    public function deleteAllProducts()
    {
        return $this->createQueryBuilder('p')
            ->delete()
            ->getQuery()->execute();
    }

    /**
     * @param string|null $term
     * @return mixed
     */
    public function findProductsWithSearch(?string $term)
    {
        $qb = $this->createQueryBuilder('p');

        if ($term !== null) {
            $qb->andWhere('p.name LIKE :term')
                ->setParameter('term' , '%' . $term . '%');
        }

        $res = $qb->orderBy('p.createdAt', 'DESC')->getQuery()->getResult();

        return $res;
    }
}
