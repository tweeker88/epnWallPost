<?php

namespace App\Controller;

use App\Api\Epn;
use App\Api\Vk;
use App\Entity\Product;
use App\Epn\clEPNAPIAccess;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{

    /**
     * @Route("/", name="index")
     * @return Response
     */
    public function index(ObjectManager $manager, Vk $vk)
    {
//        $epn = new Epn(new clEPNAPIAccess('2fa7b3ebac5a069ea822a1f6e9b3cc78', 'plstykkckaa63hps96m5phcmuewg7gc1'));
//
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

        dump($vk->sendRequest());die;

        return new Response('done');
    }
}