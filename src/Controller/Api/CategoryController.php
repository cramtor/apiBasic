<?php

namespace App\Controller\Api;

use App\Repository\BookRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use App\Service\BookFormProcessor as ServiceBookFormProcessor;
use App\Service\BookManager;
use App\Service\CategoryManager;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends AbstractFOSRestController
{
    /**
    * @Rest\Get(path="/category")
    * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks= true)
    */
    public function getAction(CategoryManager $categoryRepo){
        return $categoryRepo->getRepository()->findAll();
    }

    
        
    
}