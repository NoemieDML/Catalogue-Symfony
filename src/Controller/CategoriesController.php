<?php

namespace App\Controller;

use App\Repository\MenusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CategoriesController extends AbstractController
{
    #[Route('/categories', name: 'menus_categories')]
    public function menu_categ(MenusRepository $MenusRepository): Response
    {
        $menus = $MenusRepository->findAll();
        return $this->render('categories/categories.html.twig', [
            'Menus' => $menus,
        ]);
    }

    #[Route('/categories/{id}', name: 'categories')]
    public function categories(MenusRepository $MenusRepository, $id): Response
    {
        $menus = $MenusRepository->findBy(['categories' => $id]);
        return $this->render('categories/categories.html.twig', [
            'Menus' => $menus,
        ]);
    }
}
