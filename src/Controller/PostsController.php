<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class PostsController extends AbstractController
{
    private $postRepository;

    private $entityManager;

    private $slugger;

    public function __construct(PostRepository $postRepository, EntityManagerInterface $entityManager, SluggerInterface $slugger)
    {
        $this->postRepository = $postRepository;
        $this->entityManager = $entityManager;
        $this->slugger = $slugger;
    }

    #[Route('/posts', name: 'blog_posts')]
    public function posts(): Response
    {
        $posts = $this->postRepository->findAll();

        return $this->render('posts/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/posts/new', name: 'new_blog_post')]
    public function addPost(Request $request): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setSlug($this->slugger->slug($post->getTitle()));
            $post->setCreatedAt(new \DateTime());

            $this->entityManager->persist($post);
            $this->entityManager->flush();

            return $this->redirectToRoute('blog_posts');
        }
        return $this->render('posts/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/posts/search', name: 'blog_search')]
    public function search(Request $request): Response
    {
        $query = $request->query->get('q');
        $posts = $this->postRepository->searchByQuery($query);

        return $this->render('posts/query_post.html.twig', [
            'posts' => $posts
        ]);
    }

    #[Route('/posts/{slug}', name: 'blog_show')]
    public function post(Post $post): Response
    {
        return $this->render('posts/show.html.twig', [
            'post' => $post
        ]);
    }

    #[Route('/posts/{slug}/edit', name: 'blog_post_edit')]
    public function edit(Post $post, Request $request): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setSlug($this->slugger->slug($post->getTitle()));
            $this->entityManager->flush();

            return $this->redirectToRoute('blog_show', [
                'slug' => $post->getSlug()
            ]);
        }

        return $this->render('posts/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/posts/{slug}/delete', name: 'blog_post_delete')]
    public function delete(Post $post): Response
    {
        $this->entityManager->remove($post);
        $this->entityManager-> flush();

        return $this->redirectToRoute('blog_posts');
    }

    #[Route('/post/{slug}', methods: ['POST'], name: 'comment_new')]
    public function commentNew(Post $post, Request $request): Response
    {
        $comment = new Comment();
        $comment->setAuthor($this->getUser());
        $post->addComment($comment);

        $form = $this->createForm(CommentType::class, $comment);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($comment);
            $this->entityManager->flush();
        
            return $this->redirectToRoute('post_show', ['slug' => $post->getSlug()]);
        }

        return $this->render('/post/show.html.twig', [
            'post' => $post,
            'form' => $form->createView()
        ]);

    }
}
