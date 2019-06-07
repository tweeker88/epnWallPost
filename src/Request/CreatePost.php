<?php

namespace App\Request;

use App\Entity\Product;
use Symfony\Component\Validator\Constraints as Assert;

class CreatePost
{
    private const CHECK = 'check';
    /**
     * @Assert\NotBlank
     */
    public $content;

    /**
     * @Assert\Url
     */
    public $url;

    /**
     * @Assert\NotBlank
     */
    public $img;

    public $status = self::CHECK;

    /**
     * @param Product $product
     * @return CreatePost
     */
    public static function fromProduct(Product $product): self
    {
        $createPost = new static();

        $createPost->content =
            $product->getName() . PHP_EOL .
            'Цена: ' . $product->getPrice() . PHP_EOL .
            'Подробнее: ' . $product->getUrl();
        $createPost->url = $product->getUrl();
        $createPost->img = $product->getPicture();

        return $createPost;
    }
}