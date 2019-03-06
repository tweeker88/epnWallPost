<?php

namespace App\Controller;

use App\Api\Epn;
use App\Api\Vk;
use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\CollectionBuilder;
use App\Service\MainService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{

    /**
     * @var $vk Vk
     * @var $epn Epn
     * @var $builder MainService
     */
    private $vk;
    private $epn;
    private $builder;
    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * IndexController constructor.
     * @param Vk $vk
     * @param Epn $epn
     * @param MainService $builder
     * @param ProductRepository $productRepository
     */
    public function __construct(Vk $vk, Epn $epn, MainService $builder, ProductRepository $productRepository)
    {
        $this->vk = $vk;
        $this->epn = $epn;
        $this->builder = $builder;
        $this->productRepository = $productRepository;
    }

    /**
     * @Route("/", name="index")
     * @return Response
     */
    public function index(): Response
    {
        $products = $this->productRepository->findAll();

        return $this->render('index.html.twig', ['products' => $products, 'lastDate' => $this->builder->getDateLastParsing()]);
    }

    /**
     * @Route("/categories", name="categories")
     */
    public function addCategories(): Response
    {
        return new JsonResponse($this->epn->sendRequestCategory());
    }

    /**
     * @Route("/get-products", name="get-products", methods={"GET"})
     * @return RedirectResponse
     */
    public function getProducts(): RedirectResponse
    {
        $this->builder->getProducts();

        return new RedirectResponse($this->generateUrl('index'));
    }

    /**
     * @Route("/delete-products", name="delete-products")
     */
    public function DeleteAllProducts(): Response
    {
        $this->builder->deleteAllProducts();

        return new RedirectResponse($this->generateUrl('index'));
    }

}