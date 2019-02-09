<?php

namespace App\Service;


use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

class MainBuilder
{
    /**
     * @var integer $counter
     */
    private $counter = 0;
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
        $this->setCount();

        /** @var Product $item */
        foreach ($items as $item) {
            if ($this->productRepository->findOneBy(['productId' => $item->getProductId()]) === null) {
                $this->incrementCounter();

                $this->manager->persist($item);
            }
        }
        $this->manager->flush();
    }

    public function getCount(): int
    {
        return $this->counter;
    }

    public function setCount()
    {
        $this->counter = 0;
    }

    public function incrementCounter(): int
    {
        return $this->counter++;
    }
}