<?php

namespace App\Service;

use App\Entity\Book;
use App\Form\Model\BookDto;
use App\Form\Model\CategoryDto;
use App\Form\Type\BookFormType;
use App\Service\FileUploader;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class BookFormProcessor
{
    private $bookManager;
    private $categoryManager;
    private $fileUploader;
    private $formFactory;

    public function __construct(
        BookManager $bookManager,
        CategoryManager $categoryManager,
        FileUploader $fileUploader,
        FormFactoryInterface $formFactory
    )
    {
        $this->bookManager = $bookManager;
        $this->categoryManager = $categoryManager;
        $this->fileUploader = $fileUploader;
        $this->formFactory = $formFactory;
    }

    public function __invoke(
        Book $book,
        Request $request
    ){
                       

        $bookDto = BookDto::createFromBook($book);

        $originalCategories = new ArrayCollection();
        foreach ($book->getCategories() as $category) {
            $categoryDto = CategoryDto::createFromCategory($category);
            $bookDto->categories[] = $categoryDto;
            $originalCategories->add($categoryDto);
        }
        //
        $form = $this->formFactory->create(BookFormType::class, $bookDto);
        $form->handleRequest($request);
        if (!$form->isSubmitted()){
            return [null,'Form is not submitted'];
        }
        if ($form->isValid()) {
        
            //remove Categories
           /* foreach($originalCategories as $originalCategoryDto) {
                if (!in_array($originalCategoryDto, $bookDto->categories)){
                    $idCat= $originalCategoryDto->id;
                    $category = $this->categoryManager->find((int)$idCat);
                    sprintf(isset($category));
                    $book->removeCategory($category);
                }
            }*/

            //Add categories
            foreach($bookDto->categories as $newCategoryDto){
                if (!$originalCategories->contains($newCategoryDto)){
                    $category = $this->categoryManager->find($newCategoryDto->id ?? 0);
                    if(!$category) {
                        $category = $this->categoryManager->create();
                        $category->setName($newCategoryDto->name);
                        $this->categoryManager->persist($category);
                    }
                    $book->addCategory($category);
                }
            }
            printf($bookDto->title);
            $book->setTitle($bookDto->title);
            /* if($bookDto->base64Image)
            {
                $filename = $fileuploader->uploadBase64File($bookDto->base64Image);
                $book->setImage($fileName);
            } */ 
            $this->bookManager->save($book);
            $this->bookManager->reload($book);
            return [$book,'null'];
        }
    }
}