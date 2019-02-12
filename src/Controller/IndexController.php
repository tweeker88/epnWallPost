<?php

namespace App\Controller;

use App\Api\Epn;
use App\Api\Vk;
use App\Service\CollectionBuilder;
use App\Service\MainBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{

    /**
     * @var $vk Vk
     * @var $epn Epn
     * @var $builder MainBuilder
     */
    private $vk;
    private $epn;
    private $builder;

    /**
     * IndexController constructor.
     * @param Vk $vk
     * @param Epn $epn
     * @param MainBuilder $builder
     */
    public function __construct(Vk $vk, Epn $epn, MainBuilder $builder)
    {
        $this->vk = $vk;
        $this->epn = $epn;
        $this->builder = $builder;
    }

    /**
     * @Route("/", name="index")
     * @param CollectionBuilder $collectionBuilder
     * @return Response
     */
    public function index(CollectionBuilder $collectionBuilder): Response
    {

        $items = $this->epn->sendRequestSearch('200574005');
        $this->builder->addProducts($collectionBuilder->createCollection($items));

        return new Response('Done');
    }

    /**
     * @Route("/categories", name="categories")
     */
    public function addCategories(): Response
    {
        return new JsonResponse($this->epn->sendRequestCategory());
    }
}