<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\CreatePostType;
use App\Form\ProductType;
use App\Request\CreatePost;
use App\Service\PostCreator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{

    /**
     * @param Product $product
     * @param Request $request
     * @param PostCreator $postCreator
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/create-post/{id}", name="create_post")
     */
    public function createPost(Product $product, Request $request, PostCreator $postCreator)
    {
        $createPostRequest = CreatePost::fromProduct($product);

        $form = $this->createForm(CreatePostType::class, $createPostRequest);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $postCreator->execute($createPostRequest);
            return $this->redirectToRoute('index');
        }

        return $this->render('products/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView()
        ]);
    }
}
