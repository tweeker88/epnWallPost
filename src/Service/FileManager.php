<?php

namespace App\Service;


class FileManager
{
    private const PATH = __DIR__ . '/../../public/img';

    private function fileExist(string $name)
    {
        return file_exists(self::PATH. $name);
    }

    public function createFile(string $name, string $url)
    {
        if($this->fileExist($name)){
            return false;
        }

        return file_put_contents(self::PATH. $name, file_get_contents($url));
    }

    public function getNameFile(string $name)
    {
        return self::PATH. $name;
    }
}