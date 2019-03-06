<?php

namespace App\Service;


use App\Api\Epn;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;

class MainService
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;
    /**
     * @var ProductRepository
     */
    private $productRepository;
    /**
     * @var CollectionBuilder
     */
    private $collectionBuilder;
    /**
     * @var Epn
     */
    private $epn;

    /**
     * MainService constructor.
     * @param EntityManagerInterface $manager
     * @param ProductRepository $productRepository
     * @param CollectionBuilder $collectionBuilder
     * @param Epn $epn
     */
    public function __construct(EntityManagerInterface $manager, ProductRepository $productRepository, CollectionBuilder $collectionBuilder, Epn $epn)
    {
        $this->manager = $manager;
        $this->productRepository = $productRepository;
        $this->collectionBuilder = $collectionBuilder;
        $this->epn = $epn;
    }

    private function saveProducts(array $items): void
    {
        $this->deleteAllProducts();

        /** @var Product $item */
        foreach ($items as $item) {
            if ($this->productRepository->findOneBy(['productId' => $item->getProductId()]) === null) {
                $this->manager->persist($item);
            }
        }
        $this->manager->flush();
    }

    public function getDateLastParsing(): string
    {
        try {
            /** @var Product $firstProduct */
            $lastProduct = $this->productRepository->findLastProduct();

        } catch (NonUniqueResultException $e) {
            return $e->getMessage();
        }
        return $lastProduct->getCreatedAt();
    }

    public function getProducts(): void
    {
        $items = $this->epn->sendRequestSearch('200574005');
        $this->saveProducts($this->collectionBuilder->createCollection($items));
    }

    public function deleteAllProducts(): void
    {
        $this->productRepository->deleteAllProducts();
    }
}