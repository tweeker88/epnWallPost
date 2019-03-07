<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends AbstractController
{

    /**
     * @var $builder ProductService
     */

    private $builder;
    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * IndexController constructor.
     * @param ProductRepository $productRepository
     */
    public function __construct(ProductService $builder, ProductRepository $productRepository)
    {
        $this->builder = $builder;
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

        return $this->render('index.html.twig', ['products' => $products, 'lastDate' => $this->builder->getDateLastParsing()]);
    }

    /**
     * @Route("/get-products", name="get-products", methods={"GET"})
     * @return RedirectResponse
     */
    public function getProducts(): RedirectResponse
    {
        $this->builder->get();

        return new RedirectResponse($this->generateUrl('index'));
    }

    /**
     * @Route("/delete-products", name="delete-products")
     */
    public function DeleteAllProducts(): Response
    {
        $this->builder->deleteAll();

        return new RedirectResponse($this->generateUrl('index'));
    }

}