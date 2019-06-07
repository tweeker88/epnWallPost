<?php

namespace App\Api;


use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\FileManager;
use VK\Client\VKApiClient;

class Vk
{
    /** @var SocialInterface */
    private $vk;

    private const TOKEN = '7d9d608609d87e9dba1ccdb975fcb1839334164623e2f48749f803b258d1ae7f78d95a851fd4528924f2b';
    private const GROUP_ID = 177306035;
    /**
     * @var FileManager
     */
    private $fileManager;

    public function __construct(
        VKApiClient $VKApiClient,
        FileManager $fileManager
    ) {
        $this->vk = $VKApiClient;
        $this->fileManager = $fileManager;
    }

    public function wallPost(Product $product)
    {


        $addressServer = $this->getUploadServerForPhoto();

        $photoInServer = $this->uploadPhoto($pathToFile, $addressServer);

        $photo = $this->saveWallPhoto($photoInServer);

        return $this->createPost($photo, $product);
    }

    private function createPost($photo,Product $product)
    {
        return $this->vk->wall()->post(
            self::TOKEN,
            [
                'owner_id' => (int)('-' . self::GROUP_ID),
                'rom_group' => 1,
                'message' => $product->getName() . PHP_EOL . 'Цена: ' . $product->getPrice() . ' руб.'  . PHP_EOL . 'Подробнее: ' . $product->getUrl(),
                'attachments' => 'photo'.$photo[0]['owner_id']. '_' . $photo[0]['id'].','. $product->getUrl()
            ]
        );
    }

    private function saveWallPhoto($photoInServer)
    {
        return $this->vk->photos()->saveWallPhoto(
            self::TOKEN,
            [
                'group_id' => self::GROUP_ID,
                'photo' => $photoInServer['photo'],
                'server' => $photoInServer['server'],
                'hash' => $photoInServer['hash'],

            ]
        );
    }

    private function getUploadServerForPhoto(): array
    {
        return $this->vk->photos()->getWallUploadServer(
            self::TOKEN,
            [
                'group_id' => self::GROUP_ID
            ]);
    }

    private function uploadPhoto($file, $address)
    {
        $cfile = curl_file_create($file, 'image/jpeg', 'test.jpg');

        $ch = curl_init($address['upload_url']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array("file" => $cfile));
        $result = json_decode(curl_exec($ch), true);
        curl_close($ch);

        return $result;
    }
}