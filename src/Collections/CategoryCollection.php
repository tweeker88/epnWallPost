<?php

namespace App\Collections;



use App\Entity\Category;

class CategoryCollection extends Collection
{
    /**
     * @param array $items
     * @return array
     */
    public function create(array $items): array
    {
        if ($items['total_found'] !== 0) {
            foreach ($items['offers'] as $item) {
                $product = new Category();

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