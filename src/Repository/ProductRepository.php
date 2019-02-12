<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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

    public function findFirstProduct()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.createdAt', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();
    }


    public function findIdProducts(): array
    {
        return $this->createQueryBuilder('p')
            ->select('p.productId')
            ->getQuery()
            ->getArrayResult();
    }
}
