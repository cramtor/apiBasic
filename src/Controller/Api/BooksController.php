<?php

namespace App\Controller\Api;

use App\Repository\BookRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use App\Service\BookFormProcessor as ServiceBookFormProcessor;
use App\Service\BookManager;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;

class BooksController extends AbstractFOSRestController
{
    /**
    * @Rest\Get(path="/books")
    * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks= true)
    */
    public function getAction(BookManager $bookRepo){
        return $bookRepo->getRepository()->findAll();
    }

    /**
    * @Rest\Post(path="/books")
    * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks= true)
    */
    public function postAction(
        ServiceBookFormProcessor $bookForm,
        Request $request,
        BookManager $bookManager
    ) {
        $book = $bookManager->create();
        [$book,$error] = ($bookForm)($book, $request);
        $statusCode = $book ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        $data = $book ?? $error;
        return  View::create($data, $statusCode);
    }

    /**
    * @Rest\Post(path="/books/{id}", requirements={"id"="\d+"})
    * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks= true)
    */
    public function editAction(
        int $id,        
        BookManager $bookManager,  
        ServiceBookFormProcessor $bookForm,     
        Request $request
    )
    {        
        $book = $bookManager->find($id);

        if(!$book) {
            return View::create('Book not found', Response::HTTP_NOT_FOUND);
        }
        [$book,$error] = ($bookForm)($book,$request);
        $statusCode = $book ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        $data = $book ?? $error;
        return  View::create($data, $statusCode);
    }

    /**
    * @Rest\Get(path="/books/{id}", requirements={"id"="\d+"})
    * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks= true)
    */
    public function getSingleAction(
        int $id,        
        BookManager $bookManager,  
        ServiceBookFormProcessor $bookForm,     
        Request $request
    )
    {        
        $book = $bookManager->find($id);

        if(!$book) {
            return View::create('Book not found', Response::HTTP_NOT_FOUND);
        }
        return $book;
    }

        /**
    * @Rest\Delete(path="/books/{id}", requirements={"id"="\d+"})
    * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks= true)
    */
    public function deleteAction(
        int $id,        
        BookManager $bookManager,     
        Request $request
    )
    {        
        $book = $bookManager->find($id);

        if(!$book) {
            return View::create('Book not found', Response::HTTP_NOT_FOUND);
        }
        $bookManager->delete($book);
        return View::create(null, Response::HTTP_NO_CONTENT);
    }
        
    
}