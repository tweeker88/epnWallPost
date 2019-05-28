<?php

namespace App\Api;


use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\FileManager;
use VK\Client\VKApiClient;

class Vk implements PostingInterface
{
    /** @var PostingInterface */
    private $vk;

    private const TOKEN = 'd9ca5cf67e78e8fc8bf5023406741b087f7a0a988679f1e868c64e716056cea4c4bc03b3952b350e94c9d';
    private const GROUP_ID = '-177306035';
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

    public function sendRequest(Product $product)
    {
        if (!$this->fileManager->createFile(strrchr($product->getPicture(), '/'), $product)) {
            return false;
        }
        $pathToFile = $this->fileManager->getNameFile(strrchr($product->getPicture(), '/'));

        $answerPhoto = $this->prepareCurlForPhoto($pathToFile);

        $photo = $this->uploadPhoto($answerPhoto);

        $this->uploadProduct($photo, $product);

        sleep(5);
    }

    public function getAnswer()
    {

    }

    private function getAdressForPhoto()
    {
        return $this->vk->photos()->getMarketUploadServer(self::TOKEN, [
            'group_id' => 177306035,
            'main_photo' => 1,
        ]);
    }

    private function prepareCurlForPhoto(string $path)
    {
        $adress = $this->getAdressForPhoto();

        $cfile = curl_file_create($path, 'image/jpeg', 'test.jpg');

        $ch = curl_init($adress['upload_url']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array("file" => $cfile));
        $result = json_decode(curl_exec($ch), true);
        curl_close($ch);

        return $result;
    }

    private function uploadPhoto(array $answerPhoto)
    {
        return $this->vk->photos()->saveMarketPhoto(self::TOKEN, [
            'group_id' => 177306035,
            'photo' => $answerPhoto['photo'],
            'server' => $answerPhoto['server'],
            'hash' => $answerPhoto['hash'],
            'crop_data' => $answerPhoto['crop_data'],
            'crop_hash' => $answerPhoto['crop_hash'],
        ]);
    }

    private function uploadProduct($photo,Product $product)
    {
        $this->vk->market()->add(self::TOKEN, [
            'owner_id' => self::GROUP_ID,
            'name' => $product->getName(),
            'description' => $product->getName(),
            'category_id' => 1,
            'price' => $product->getSalePrice(),
            'main_photo_id' => $photo[0]['id'],
            'url' => $product->getUrl()
        ]);
    }
}