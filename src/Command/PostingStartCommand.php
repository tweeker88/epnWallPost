<?php

namespace App\Command;

use App\Api\PostingerInterface;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PostingStartCommand extends Command
{
    protected static $defaultName = 'posting:start';
    /**
     * @var PostingerInterface
     */
    private $postinger;
    /**
     * @var ProductRepository
     */
    private $productRepository;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(?string $name = null, PostingerInterface $postinger, ProductRepository $productRepository, EntityManagerInterface $em)
    {
        parent::__construct($name);
        $this->postinger = $postinger;
        $this->productRepository = $productRepository;
        $this->em = $em;
    }

    protected function configure()
    {
        $this
            ->setDescription('Start posting in VK');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /**@var Product $product*/
        $product = $this->productRepository->findProductForPosting();

        $this->postinger->post($product);

        if($this->postinger->handleResponse()){
            $product->setStatus('post');
            $this->em->flush();
        }
    }
}
