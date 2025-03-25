<?php

namespace App\Controller;

use App\Entity\Menus;
use App\Entity\Categories;
use App\Form\CategorieFormType;
use App\Repository\MenusRepository;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

// #[Route('/admin')]
// #[IsGranted('ROLE_ADMIN')]
final class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin')]
    public function admin(): Response
    {

        return $this->render('admin/admin.html.twig');
    }

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

    // #[Route('/add_categorie_form', name: 'add_categories_form')]
    // public function add_form(Request $request, EntityManagerInterface $entityManager): Response
    // {

    //     $categ = new Categories();

    //     $form = $this->createForm(CategorieFormType::class, $categ);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->persist($categ);
    //         $entityManager->flush();
    //     }

    //     return $this->render('admin/admin.html.twig', [
    //         'controller_name' => 'categoriesController',
    //         'form' => $form->createView()
    //     ]);
    // }

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

    #[Route('/admin/add_categories', name: 'add_categories')]
    public function add_categ(CategoriesRepository $CategoriesRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $categs = $CategoriesRepository->findAll();

        $categ = new Categories();
        $form = $this->createForm(CategorieFormType::class, $categ);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($categ);
            $entityManager->flush();
        }

        return $this->render('admin/add_categ.html.twig', [
            'controller_name' => 'categoriesController',
            'form' => $form->createView(),
            'categories' => $categs
        ]);
    }

    #[Route('/admin/categories', name: 'admin_categories')]
    public function categories(CategoriesRepository $categoriesRepository): Response
    {
        $categorie = $categoriesRepository->findAll();

        return $this->render('admin/categ-list.html.twig', [
            'categorie' => $categorie,
        ]);
    }

    #[Route('/admin/menus', name: 'admin_menus')]
    public function index(MenusRepository $menusRepository): Response
    {
        $menus = $menusRepository->findAll();

        return $this->render('admin/menus-list.html.twig', [
            'menus' => $menus,
        ]);
    }
}
