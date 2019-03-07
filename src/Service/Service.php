<?php

namespace App\Service;


use App\Api\Epn;
use App\Collections\Collection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;

abstract class Service
{
    protected $manager;
    protected $repository;
    protected $collectionBuilder;
    protected $epn;

    public function __construct(EntityManagerInterface $manager, ServiceEntityRepository $repository, Collection $collectionBuilder, Epn $epn)
    {
        $this->manager = $manager;
        $this->repository = $repository;
        $this->collectionBuilder = $collectionBuilder;
        $this->epn = $epn;
    }

    abstract public function save(array $items);

    abstract public function getDateLastParsing();

    abstract public function get();

    abstract public function deleteAll();
}