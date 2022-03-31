<?php

namespace App\Controller\Api;

use App\Form\Model\CategoryDto;
use App\Form\Type\CategoryFormType;
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
    public function getAction(CategoryManager $categoryManager){
        return $categoryManager->getRepository()->findAll();
    }

    /**
    * @Rest\Post(path="/category")
    * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks= true)
    */
    public function postAction(
        Request $request,
        CategoryManager $categoryManager
    ) {
        $categoryDto = new CategoryDto;
        $form = $this->createForm(CategoryFormType::class, $categoryDto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category= $categoryManager->create();
            $category->setName($categoryDto->name);
            $categoryManager->save($category);
            return $category;
        }
        return $form;
    }    
}