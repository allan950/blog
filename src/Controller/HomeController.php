<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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

    #[Route('/post/{id<[0-9]+>}', name: 'show')]
    public function show(Post $post, CommentRepository $commentRepository, Request $request, EntityManagerInterface $em) {

        $comments = $commentRepository->findAll();

        if ($this->getUser()) {

            $comment = new Comment($this->getUser());
            $comment->setPost($post);

            // $form = $this->createFormBuilder($comment)
            // ->add('content', TextType::class)
            // //->add('post', )
            // ->add('submit', SubmitType::class)
            // ->getForm()
            // ;

            // $formView = $form->createView();

            $form = $this->createForm(CommentType::class, $comment);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $comment->setCreatedAt(new DateTime());
                $em->persist($comment);
                $em->flush();

                return $this->redirectToRoute('show', ['id' => $post->getId()]);
            }
        }

        return $this->render('home/show.html.twig', [
            'post' => $post,
            'comments' => $comments,
            'form' => $form ?? null,
        ]);
    }
}