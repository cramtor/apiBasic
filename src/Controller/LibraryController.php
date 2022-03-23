<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Log\Logger;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class LibraryController extends AbstractController
{

    /**
     * @Route("/books", name="books_get")
     */
    Public function list(Request $request, LoggerInterface $logger, BookRepository $bookRepo) {

        $title = $request->get('title','La fundación');
        $books = $bookRepo->findAll();
        $booksAr = [];
        foreach ($books as $book) {
            $booksAr[] = [
                'id' => $book->getId(),
                'title' => $book->getTitle(),
                'image' => $book->getImage()
            ];
        }
        $logger->info('list action');
        $response = new JsonResponse();
        $response->setData([
            'succes' => true,
            'data' =>$booksAr
        ]);
        return $response;
    }

    /**
     * @Route("/book/create", name="create_book")
     */
    public function createBook(Request $request, EntityManagerInterface $em) {
        $book = new Book();
        $response = new JsonResponse();
        $title = $request->get('title', null);
        if(empty($title)){
            $response->setData([
                'succes' => false,
                'title'  => 'Title cannot be empty',
                'data'   => null
            ]);
            return $response;
        }
        $book->setTitle($title);
        $em->persist($book);
        $em->flush();        
        $response->setData([
            'succes' => true,
            'data' =>
                [
                    'id' => $book->getId(),
                    'title' => $book->getTitle()
                ]            
        ]);
        return $response;
    }
}