<?php

namespace App\Service;

use App\Api\Epn;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;

class ProductService
{

    /**
     * @var EntityManagerInterface
     */
    private $manager;
    /**
     * @var ProductRepository
     */
    private $repository;
    /**
     * @var Epn
     */
    private $epn;

    public function __construct(EntityManagerInterface $manager, ProductRepository $repository, Epn $epn)
    {

        $this->manager = $manager;
        $this->repository = $repository;
        $this->epn = $epn;
    }

    public function save(array $items): void
    {
        $this->deleteAll();

        /** @var Product $item */
        foreach ($items as $item) {
            if ($this->repository->findOneBy(['productId' => $item->getProductId()]) === null) {
                $this->manager->persist($item);
            }
        }
        $this->manager->flush();
    }

    public function getDateLastParsing()
    {
        try {
            /** @var Product $firstProduct */
            $lastProduct = $this->repository->findLastProduct();

        } catch (NonUniqueResultException $e) {
            return null;
        }
        return $lastProduct->getCreatedAt();
    }

    public function get(): void
    {
        $items = $this->epn->sendRequestSearch('200574005');

        $this->collectionBuilder->create($items);

        $this->save($this->collectionBuilder->getCollection());
    }

    public function deleteAll(): void
    {
        $this->repository->deleteAllProducts();
    }
}