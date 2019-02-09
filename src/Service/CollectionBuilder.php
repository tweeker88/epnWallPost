<?php

namespace App\Service;


use App\Entity\Product;

class CollectionBuilder
{
    /**
     * @var array $collection
     */
    private $collection;

    /**
     * @param array $items
     * @return array
     */
    public function createCollection(array $items): array
    {
        if ($items['total_found'] !== 0) {
            foreach ($items['offers'] as $item) {
                $product = new Product();

                $product->setName($item['name'])
                    ->setPrice($item['price'])
                    ->setProductId($item['product_id'])
                    ->setPicture($item['picture'])
                    ->setSalePrice($item['sale_price'])
                    ->setUrl($item['url']);

                $this->collection[] = $product;
            }
        }

        return $this->collection;
    }
}