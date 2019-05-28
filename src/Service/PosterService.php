<?php

namespace App\Service;


use App\Api\PostingInterface;
use App\Api\Vk;
use App\Entity\Product;
use App\Repository\ProductRepository;

class PosterService
{
    /**
     * @var Vk
     */
    private $posting;
    /**
     * @var ProductRepository
     */
    private $productRepository;

    public function __construct(
        PostingInterface $posting,
        ProductRepository $productRepository
    ) {
        $this->posting = $posting;
        $this->productRepository = $productRepository;
    }

    public function run()
    {
        $products = $this->productRepository->findProductsForPosting();

        if ($this->isEmpty($products)) {
            return false;
        }
        /* @var Product $product */
        foreach ($products as $product) {
            $this->posting->sendRequest($product);
        }
    }

    private function isEmpty($products)
    {
        return $products === null;
    }
}