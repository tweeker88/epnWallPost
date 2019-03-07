<?php

namespace App\Collections;


abstract class Collection
{
    protected $collection;

    abstract public function create(array $array): array;

    public function getCollection()
    {
        return $this->collection;
    }
}