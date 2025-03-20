<?php

namespace App\Controller;

use App\Repository\MenusRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class MenusController extends AbstractController
{
    #[Route('/menus', name: 'menus')]
    public function index(MenusRepository $MenusRepository): Response
    {
        $menus = $MenusRepository->findAll();
        return $this->render('menus/menus.html.twig', [
            'Menus' => $menus,
        ]);
    }
}
