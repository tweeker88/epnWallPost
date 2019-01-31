<?php

namespace App\Controller;

use App\Api\Epn;
use App\Api\Vk;
use App\Entity\Product;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{

    /**
     * @Route("/", name="index")
     * @return Response
     * @var ObjectManager $manager
     * @var Vk $vk
     * @var Epn $epn
     */
    public function index(ObjectManager $manager, Vk $vk, Epn $epn): Response
    {
        $epn->sendRequest();
//        if ($epn->sendRequest()) {
//            $items = $epn->getAnswer();
//            foreach ($items['offers'] as $item) {
//                $product = new Product();
//
//                $product->setName($item['name'])
//                    ->setPrice($item['price'])
//                    ->setProductId($item['product_id'])
//                    ->setPicture($item['picture'])
//                    ->setSalePrice($item['sale_price'])
//                    ->setUrl($item['url']);
//
//                $manager->persist($product);
//            }
//
//            $manager->flush();
//        }
//
//        dump($vk->sendRequest());die;

        return new Response('done');
    }
}