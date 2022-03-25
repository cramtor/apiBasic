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
use League\Flysystem\FilesystemOperator;

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
        Request $request
    ) {
        $bookDto = new BookDto();
        $form = $this->createForm(BookFormType::class, $bookDto);        
        $form->handleRequest($request);
        printf('hola');
        if($form->isSubmitted() && $form->isValid()){
            /*$extension = explode('/',mime_content_type($bookDto->base64Image))[1];
            $data = explode('+',$bookDto->base64Image);
            $fileName = sprintf('%s.%s', uniqid('book_', true), $extension);
            $defaultStorage->write($fileName, base64_decode($data[1]));*/
            $book = new Book();
            //$book->setImage($fileName);
            $book->setTitle($bookDto->title);
            $em->persist($book);
            $em->flush(); 
            return $book;           
        }
        return $form;
    }
}