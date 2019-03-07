<?php

namespace App\Controller;

use App\Api\Epn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{

    /**
     * @var Epn
     */
    private $epn;

    public function __construct(Epn $epn)
    {

        $this->epn = $epn;
    }

    /**
     * @Route("/category", name="category")
     */
    public function index()
    {
        dd($this->epn->sendRequestCategory());
    }

    public function getCategories()
    {

    }
}
