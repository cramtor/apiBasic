<?php

namespace App\Service\Book;

use App\Entity\Book;
use App\Entity\Category;
use App\Form\Model\BookDto;
use App\Form\Model\CategoryDto;
use App\Form\Type\BookFormType;
use App\Repository\BookRepository;
use App\Repository\CategoryRepository;
use App\Service\FileUploader;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class BookFormProcessor
{
    private $em;
    private $bookRepository;
    private $categoryRepository;

    public function __contruct(
)
    {
    }

    public function processBook(
        Request $request,
        BookRepository $bookRepository,
        EntityManagerInterface $em,
        CategoryRepository $categoryRepository,
        $bookId)
    {        
        printf($bookId);
        $book = $bookRepository->find($bookId); 
                       
        if(!$book) {
            return [null, 'not found'];
        }
        $bookDto = BookDto::createFromBook($book);

        $originalCategories = new ArrayCollection();
        foreach ($book->getCategories() as $category) {
            $categoryDto = CategoryDto::createFromCategory($category);
            $bookDto->categories[] = $categoryDto;
            $originalCategories->add($categoryDto);
        }
    
        //remove Categories
        foreach($originalCategories as $originalCategoryDto) {
            if (!in_array($originalCategoryDto, $bookDto->categories)){
                $category = $categoryRepository->find($originalCategoryDto->id);
                $book->removeCategory($category);
            }
        }

        //Add categories
        foreach($bookDto->categories as $newCategoryDto){
            if (!$originalCategories->contains($newCategoryDto)){
                $category = $categoryRepository->find($newCategoryDto->id ?? 0);
                if(!$category) {
                    $category = new Category();
                    $category->setName($newCategoryDto->name);
                    $em->persist($category);
                }
                $book->addCategory($category);
            }
        }
        $book->setTitle($bookDto->title);
        /* if($bookDto->base64Image)
        {
            $filename = $fileuploader->uploadBase64File($bookDto->base64Image);
            $book->setImage($fileName);
        } */ 
        $em->persist($book);
        $em->flush();
        $em->refresh($book);
        return $book;
        

    }
}