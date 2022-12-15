<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Post;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController {

    public function __construct(private CategoryRepository $categoryRepository, private PostRepository $postRepository)
    {
        
    }

    #[Route('/', name: 'home')]
    public function index(PostRepository $postRepo): Response {

        $categories = $this->categoryRepository->findAll();
        $posts = $postRepo->findAll();

        return $this->render('home/index.html.twig', [
            'posts' => $posts,
            'categories' => $categories,
        ]);
    }

    #[Route('/post/category/{id<[0-9]+>}', name: 'index_by_category')]
    public function indexByCategory(Category $category) {

        return $this->render('home/index.html.twig', [
            'posts' => $category->getPosts(),
            'categories' => $this->categoryRepository->findAll(),
        ]);
    }

    #[Route('/post/search', name: 'index_by_search')]
    public function indexBySearch(Request $request) {

        $search = $request->request->get('search');

        $category = $this->postRepository->findAllBySearch($search);
        
        return $this->render('home/index.html.twig', [
            'posts' => $category,
            'categories' => $this->categoryRepository->findAll(),
        ]);
    }

    #[Route('/post/{id<[0-9]+>}')]
    public function show(Post $post) {

        return $this->render('home/show.html.twig', [
            'post' => $post,
        ]);
    }
}