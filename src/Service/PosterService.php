<?php

namespace App\Service;


use App\Api\PostingInterface;
use App\Entity\Product;
use App\Repository\ProductRepository;

class PosterService
{
    /**
     * @var PostingInterface
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
        do {
            /* @var Product $product */

            $product = $this->productRepository->findProductForPosting();

            if ($this->isEmpty($product)) {
                return false;
            }
            $this->posting->sendRequest($product);
        } while ($this->posting->getAnswer());
    }

    private function isEmpty($product)
    {
        return $product === null;
    }
}