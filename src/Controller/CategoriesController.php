<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Service\CategoryService;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class CategoriesController extends AbstractController
{

    /**
     * @var CategoryRepository
     */
    private $categoryRepository;
    /**
     * @var CategoryService
     */
    private $service;
    /**
     * @var ProductService
     */
    private $productService;

    public function __construct(CategoryRepository $categoryRepository, CategoryService $service, ProductService $productService)
    {

        $this->categoryRepository = $categoryRepository;
        $this->service = $service;
        $this->productService = $productService;
    }

    /**
     * @Route("/categories", name="categories")
     */
    public function index(Request $request): Response
    {
        $categories = $this->categoryRepository->findCategoriesWithSearch($request->query->get('q'));

        return $this->render('categories/index.html.twig', [
            'categories' => $categories,
            'lastDate' => $this->service->getDateLastParsing()
        ]);
    }

    /**
     * @Route("/get-categories", name="get-categories", methods={"GET"})
     * @return RedirectResponse
     */
    public function getCategories(): RedirectResponse
    {
        $this->service->getCategoriesFromEpn();

        return new RedirectResponse($this->generateUrl('categories'));
    }

    /**
     * @Route("/delete-categories", name="delete-categories")
     */
    public function DeleteAllCategories(): Response
    {
        $this->service->deleteAllCategories();

        return new RedirectResponse($this->generateUrl('categories'));
    }

    /**
     * @Route("/get-products-of-this-category-{idCategory}", name="get-products-of-this-category")
     * @param string $idCategory
     * @return Response
     */
    public function getProductsOfThisCategory(string $idCategory): Response
    {
        $this->productService->getProducts($idCategory);

        return new RedirectResponse($this->generateUrl('index'));
    }
}
