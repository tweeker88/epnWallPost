<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Service\ProductService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @var $service ProductService
     */
    private $service;
    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * IndexController constructor.
     * @param ProductService $service
     * @param ProductRepository $productRepository
     */
    public function __construct(ProductService $service, ProductRepository $productRepository)
    {
        $this->service = $service;
        $this->productRepository = $productRepository;
    }

    /**
     * @Route("/", name="index")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $products = $this->productRepository->findProductsWithSearch($request->query->get('q'));

        return $this->render('index.html.twig',
            ['products' => $products, 'lastDate' => $this->service->getDateLastParsing()]);
    }

    /**
     * @Route("/delete-products", name="delete-products")
     */
    public function DeleteAllProducts(): Response
    {
        $this->service->deleteAllProducts();

        return $this->redirectToRoute('index');
    }
}