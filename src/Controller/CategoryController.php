<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="category")
     */
    public function index(): Response
    {
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }

    public function new(string $name, string $color){
        $category = new Category();
        $category
            -> setName($name)
            -> setColor($color)
        ;
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($category);
        $manager->flush();

        die('Catégorie ajoutée');
    }

    public function showAllCategories(CategoryRepository $categories){
        $category = $categories->findAll();

        dd($category);
    }

    public function categoryDetails(int $id, CategoryRepository $categories){
        $details = $categories->findOneBy([
            'id' => $id
        ]);

        dd($details);
    }
}
