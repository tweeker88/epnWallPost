<?php

namespace App\Controller;

use App\Epn\clEPNAPIAccess;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{

    /**
     * @Route("/", name="index")
     * @return Response
     */
    public function index()
    {



       $items = [];

       foreach ($result['offers'] as $item){
        $items[$item['id']] = $item;
       }

       return $this->render('index.html.twig', ['items' => $items]);
    }
}