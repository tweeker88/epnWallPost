<?php

namespace App\Service;


use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

class MainBuilder
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
     * MainBuilder constructor.
     * @param EntityManagerInterface $manager
     * @param ProductRepository $productRepository
     */
    public function __construct(EntityManagerInterface $manager, ProductRepository $productRepository)
    {
        $this->manager = $manager;
        $this->productRepository = $productRepository;
    }

    public function addProducts(array $items): void
    {
        /** @var Product $item */
        foreach ($items as $item) {
            if ($this->productRepository->findOneBy(['productId' => $item->getProductId()]) === null) {
                $this->manager->persist($item);
            }
        }
        $this->manager->flush();
    }
}