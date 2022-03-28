<?php

namespace App\Controller\Api;

use App\Repository\BookRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Book;
use App\Form\Model\BookDto;
use App\Form\Type\BookFormType;
use App\Service\FileUploader;
use League\Flysystem\FilesystemOperator;
use Symfony\Component\HttpFoundation\Response;

class BooksController extends AbstractFOSRestController
{
    /**
    * @Rest\Get(path="/books")
    * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks= true)
    */
    public function getAction(BookRepository $bookRepo){
        return $bookRepo->findAll();
    }

    /**
    * @Rest\Post(path="/books")
    * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks= true)
    */
    public function postAction(
        EntityManagerInterface $em,
        FilesystemOperator $defaultStorage,
        FileUploader $fileUploader,
        Request $request
    ) {
        $bookDto = new BookDto();
        $form = $this->createForm(BookFormType::class, $bookDto);        
        $form->handleRequest($request);
        if (!$form->isSubmitted())
        {
            return new Response('', Response::HTTP_BAD_REQUEST);
        }
        if( $form->isValid()){
            $book = new Book();
           /* if($bookDto->base64Image)
            {
                $filename = $fileuploader->uploadBase64File($bookDto->base64Image);
                $book->setImage($fileName);
            } */     
            $book->setTitle($bookDto->title);
            $em->persist($book);
            $em->flush(); 
            return $book;           
        }
        return $form;
    }
}