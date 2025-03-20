<?php

namespace App\Controller;

use App\Entity\Menus;
use App\Entity\Categories;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class AdminController extends AbstractController
{
    #[Route('/add_categories/{name}', name: 'add_categories')]
    public function add(EntityManagerInterface $entitymanager, $name): Response
    {

        $categ = new Categories();
        $categ->setName($name);

        //persit sert à mettre en  fil d'attente ce qui va aller
        //en base de données
        //ATTENTION -> je ne peux pas persist le vide
        // forcément ue entitée
        $entitymanager->persist($categ);

        //rien besoins en parametre ! il execute juste la file d'attente
        $entitymanager->flush();

        return $this->redirectToRoute('admin');
    }

    #[Route('/remove_categories/{id}', name: 'remove_categories')]
    public function remove_categ(EntityManagerInterface $entitymanager, CategoriesRepository $categories_repository, $id): Response
    {
        $categ = $categories_repository->find($id);

        $entitymanager->remove($categ);

        $entitymanager->flush();


        return $this->redirectToRoute('admin');
    }

    #[Route('/edit_categories/{id}/{name}', name: 'edit_categories')]
    public function edit_categ(EntityManagerInterface $entitymanager, CategoriesRepository $categories_repository, $name, $id): Response
    {
        $categ = $categories_repository->find($id);

        $categ->setLabel($name);
        $entitymanager->persist($categ);
        $entitymanager->flush();

        return $this->render('admin/admin.html.twig');
    }

    #[Route('/admin/categories', name: 'admin')]
    public function index(CategoriesRepository $CategoriesRepository): Response
    {
        $categories = $CategoriesRepository->findAll();
        return $this->render('admin/admin.html.twig', [
            'categories' => $categories,
        ]);
    }
}
