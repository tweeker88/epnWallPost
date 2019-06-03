<?php

namespace App\Api;


use App\Entity\Product;

interface PostingerInterface
{
    public function post(Product $product);

    public function handleResponse();
}