<?php

namespace App\Controller;

use App\Entity\Menus;
use App\Form\MenusType;
use App\Repository\MenusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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
            'menus' => $menus,
        ]);
    }

    #[Route('/add_menus', name: 'add_menus')]
    public function add_menus(
        MenusRepository $MenusRepository,
        EntityManagerInterface $entityManager,
        Request $request
    ): Response {

        $menus = new Menus();
        $form = $this->createForm(MenusType::class, $menus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($menus);
            $entityManager->flush();
        }

        return $this->render('admin/add_menus.html.twig', [
            'menus' => $menus,
            'form' => $form->createView()
        ]);
    }


    #[Route('/remove_menus/{id}', name: 'remove_menus')]
    public function remove_menus(EntityManagerInterface $entitymanager, MenusRepository $menus_repository, $id): Response
    {
        $menus = $menus_repository->find($id);

        $entitymanager->remove($menus);

        $entitymanager->flush();


        return $this->redirectToRoute('admin');
    }

    #[Route('/edit_menus/{id}/{name}', name: 'edit_menus')]
    public function edit_menus(EntityManagerInterface $entitymanager, MenusRepository $menus_repository, $name, $id): Response
    {
        $menus = $menus_repository->find($id);

        $menus->setLabel($name);
        $entitymanager->persist($menus);
        $entitymanager->flush();

        return $this->render('admin/add_menus.html.twig');
    }
}
