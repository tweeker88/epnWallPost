<?php

namespace App\Service;


use App\Api\Epn;
use App\Entity\Product;
use App\Repository\ProductRepository;
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
     * ProductService constructor.
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

    public function getDateLastParsing()
    {
        try {
            /** @var Product $lastProduct */
            $lastProduct = $this->productRepository->findLastProduct();
        } catch (NonUniqueResultException $e) {
            return null;
        }

        return $lastProduct->getCreatedAt();
    }

    public function getProducts($idCategory): void
    {
        $items = $this->epn->sendRequestSearch($idCategory);

        $this->saveProducts($this->collectionBuilder->createCollectionProduct($items));
    }

    public function deleteAllProducts(): void
    {
        $this->productRepository->deleteAllProducts();
    }
}