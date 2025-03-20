<?php

namespace App\Controller;

use App\Repository\MenusRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ShowController extends AbstractController
{
    #[Route('menus_show/{id}', name: 'menus_show')]
    public function show(MenusRepository $MenusRepository, $id): Response
    {
        $menu = $MenusRepository->find($id);
        return $this->render('show/show.html.twig', [
            'menu' => $menu,
        ]);
    }
}
