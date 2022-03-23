<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class LibraryController extends AbstractController
{
    /**
     * @Route("/library/list", name="library_list")
     */
    Public function list() {
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
                ]
            ]
        ]);
        return $response;
    }
}