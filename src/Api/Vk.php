<?php

namespace App\Api;


use App\Entity\Product;
use App\Repository\ProductRepository;
use VK\Client\VKApiClient;

class Vk
{
    /** @var VKApiClient */
    private $vk;

    /** @var ProductRepository */
    private $productsRepository;

    private const TOKEN = '40615bd69d9c9e3780468bdf0937829a50bbd6300e1a49ec9016d383ce28d7e74ac72718627c2eef15569';
    private const GROUP_ID = '-177306035';

    public function __construct(VKApiClient $VKApiClient, ProductRepository $productRepository)
    {
        $this->vk = $VKApiClient;
        $this->productsRepository = $productRepository;
    }

    public function sendRequest() //@TODO рефакторинг ЖЕСТКИЙ
    {
        /**
         * @var $product Product
         */
        $products = $this->productsRepository->findAll();

        if ($products === null) {
            return false;
        }

        foreach ($products as $product) {

            $name = strrchr($product->getPicture(), '/');
            $path = __DIR__ . '/../../public/img' . $name;

            if(!is_file($path)){
                file_put_contents($path, file_get_contents($product->getPicture())); //@TODO Заменить на более легкое
            }

            $adress = $this->vk->photos()->getMarketUploadServer(self::TOKEN, [
                'group_id' => 177306035,
                'main_photo' => 1,
            ]);

            $cfile = curl_file_create($path, 'image/jpeg', 'test.jpg');
            $ch = curl_init($adress['upload_url']);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, array("file" => $cfile));
            $result = json_decode(curl_exec($ch), true);
            curl_close($ch);

            $photo = $this->vk->photos()->saveMarketPhoto(self::TOKEN, [
                'group_id' => 177306035,
                'photo' => $result['photo'],
                'server' => $result['server'],
                'hash' => $result['hash'],
                'crop_data' => $result['crop_data'],
                'crop_hash' => $result['crop_hash'],
            ]);

            if (strlen($product->getName()) >= 100) {
                $product->setName(substr($product->getName(), 0, 70));
            }
            $this->vk->market()->add(self::TOKEN, [
                'owner_id' => self::GROUP_ID,
                'name' => $product->getName(),
                'description' => $product->getName(),
                'category_id' => 1,
                'price' => $product->getSalePrice(),
                'main_photo_id' => $photo[0]['id'],
                'url' => $product->getUrl()
            ]);
            sleep(5);
        }

    }

    public function getAnswer()
    {

    }
}