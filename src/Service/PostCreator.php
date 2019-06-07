<?php

namespace App\Service;

use App\Entity\Post;
use App\Request\CreatePost;
use Doctrine\ORM\EntityManagerInterface;

class PostCreator
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var Post
     */
    private $post;
    /**
     * @var FileManager
     */
    private $fileManager;

    public function __construct(EntityManagerInterface $em, Post $post, FileManager $fileManager)
    {
        $this->em = $em;
        $this->post = $post;
        $this->fileManager = $fileManager;
    }

    public function execute(CreatePost $createPost)
    {
        $post = Post::create(
            $createPost->content,
            $createPost->url,
            $this->createPathToImg($createPost->img),
            $createPost->status
        );

        try {
            $this->em->persist($post);
            $this->em->flush();
        } catch (\Exception $exception) {
            return false;
        }
        return true;
    }

    private function createPathToImg(string $url)
    {
        $this->fileManager->createFile(strrchr($url, '/'), $url);

        return $this->fileManager->getNameFile(strrchr($url, '/'));
    }
}