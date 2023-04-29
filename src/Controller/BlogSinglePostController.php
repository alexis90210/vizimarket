<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogSinglePostController extends AbstractController
{
    #[Route('/blog-single-post', name: 'app_blog_single_post')]
    public function index(): Response
    {
        return $this->render('blog_single_post/index.html.twig', [
            'controller_name' => 'BlogSinglePostController',
        ]);
    }
}
