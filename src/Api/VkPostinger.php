<?php

namespace App\Api;


use App\Entity\Product;

class VkPostinger implements PostingerInterface
{
    /* @var Vk*/
    private $vk;

    private $response;

    public function __construct(Vk $vk)
    {
        $this->vk = $vk;
    }

    public function post(Product $product)
    {
        $this->response = $this->vk->wallPost($product);
    }

    public function handleResponse()
    {
        return count($this->response) > 0;
    }
}