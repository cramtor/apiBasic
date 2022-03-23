<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Log\Logger;
use Symfony\Component\Routing\Annotation\Route;

class LibraryController extends AbstractController
{

    /**
     * @Route("/library/list", name="library_list")
     */
    Public function list(Request $request, LoggerInterface $logger) {

        $title = $request->get('title','La fundación');
        $logger->info('list action');
        $response = new JsonResponse();
        $response->setData([
            'succes' => true,
            'data' =>[
                [
                    'id' => 1,
                    'title' => 'Normal people'
                ],
                [
                    'id' => 2,
                    'title' => 'El millor dels mons'
                ],
                [
                    'id' => 3,
                    'title' => $title
                ]
            ]
        ]);
        return $response;
    }
}