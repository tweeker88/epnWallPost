<?php

namespace App\Service;


use App\Entity\Product;

class FileManager
{
    private const PATH = __DIR__ . '/../../public/img';

    private function fileExist(string $name)
    {
        return file_exists(self::PATH. $name);
    }

    public function createFile(string $name, Product $product)
    {
        if($this->fileExist($name)){
            return false;
        }

        return file_put_contents(self::PATH. $name, file_get_contents($product->getPicture()));
    }

    public function getNameFile($name)
    {
        return self::PATH. $name;
    }
}