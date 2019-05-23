<?php

namespace App\Service;


use App\Api\Epn;
use App\Entity\Category;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;

class CategoryService
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;
    /**
     * @var CollectionBuilder
     */
    private $collectionBuilder;
    /**
     * @var Epn
     */
    private $epn;

    /**
     * ProductService constructor.
     * @param EntityManagerInterface $manager
     * @param CategoryRepository $categoryRepository
     * @param CollectionBuilder $collectionBuilder
     * @param Epn $epn
     */
    public function __construct(EntityManagerInterface $manager, CategoryRepository $categoryRepository, CollectionBuilder $collectionBuilder, Epn $epn)
    {
        $this->manager = $manager;
        $this->categoryRepository = $categoryRepository;
        $this->collectionBuilder = $collectionBuilder;
        $this->epn = $epn;
    }

    private function saveCategories(array $items): void
    {
        $this->deleteAllCategories();

        /** @var Category $item */
        foreach ($items as $item) {
            if ($this->categoryRepository->findOneBy(['id_epn' => $item->getIdEpn()]) === null) {
                $this->manager->persist($item);
            }
        }
        $this->manager->flush();
    }

    public function getDateLastParsing()
    {
        try {
            /** @var Category $lastCategory */
            $lastCategory = $this->categoryRepository->findLastProduct();

        } catch (NonUniqueResultException $e) {
            return null;
        }

        return $lastCategory->getCreatedAt();
    }

    public function getCategoriesFromEpn(): void
    {
        $items = $this->epn->sendRequestCategory();

        $this->saveCategories($this->collectionBuilder->createCollectionCategory($items));
    }

    public function deleteAllCategories(): void
    {
        $this->categoryRepository->deleteAllCategories();
    }
}