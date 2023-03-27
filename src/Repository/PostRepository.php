<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;

class PostRepository
{
    private $entityManager;

    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(Post::class);
    }

    public function add(Post $post): void
    {
        $this->entityManager->persist($post);
    }

    public function findOneBySlug(string $slug)
    {
        return $this->repository->findOneBy(['slug' => $slug]);
    }
}
