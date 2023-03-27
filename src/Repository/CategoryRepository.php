<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;

class CategoryRepository
{
    private $entityManager;
    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(Category::class);
    }

    public function add(Category $category): void
    {
        $this->entityManager->persist($category);
    }

    public function findOneByName(string $name)
    {
        return $this->repository->findOneBy(['name' => $name]);
    }
}
