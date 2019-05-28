<?php

namespace App\Api;


use App\Entity\Product;

interface PostingInterface
{
 public function sendRequest(Product $product);

 public function getAnswer();
}