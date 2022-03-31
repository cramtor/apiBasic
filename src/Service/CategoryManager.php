<?php

namespace App\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;

class CategoryManager
{
    private $em;
    private $categoryRepository;

    public function __construct(
        CategoryRepository $categoryRepository,
        EntityManagerInterface $em
    )
    {
        $this->em = $em;
        $this->categoryRepository = $categoryRepository;
    }

    public function find(int $id) : ?Category
    {
        $category = $this->categoryRepository->find($id);

        return $category;
    }

    public function getRepository():?CategoryRepository
    {
        return $this->categoryRepository;
    }

    public function create()
    {
       $category = new Category();
       return $category; 
    }

    public function persist(Category $category)
    {
        $this->em->persist($category);
        return $category;
    }

    public function save(Category $category)
    {
        $this->em->persist($category);
        $this->em->flush();
        return $category;
    }

    public function reload(Category $category)
    {
        $this->em->refresh($category);
        return $category;
    }
}