<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AuthorController extends AbstractController
{


    public $authors = array(
        array('id' => 1, 'picture' => '/images/Victor-Hugo.jpg', 'username' => 'Victor Hugo', 'email' =>
        'victor.hugo@gmail.com ', 'nb_books' => 100),
        array('id' => 2, 'picture' => '/images/william-shakespeare.jpg', 'username' => ' William Shakespeare', 'email' =>
        ' william.shakespeare@gmail.com', 'nb_books' => 200),
        array('id' => 3, 'picture' => '/images/Taha_Hussein.jpg', 'username' => 'Taha Hussein', 'email' =>
        'taha.hussein@gmail.com', 'nb_books' => 10),


    );
    #[Route('/a', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }




    #[Route('/show/{name}')]
    public function showAuthor($name)
    {


        return $this->render('author/show.html.twig', ["name" => $name]);
    }






    #[Route('/details/{id}', name: "details")]
    public function authorDetails($id)
    {

        $author = null;


        foreach ($this->authors as $a) {


            if ($a['id'] == $id) {

                $author = $a;
            }
        }


        return $this->render('author/showAuthor.html.twig', ["author" => $author]);
    }




    #[Route('/listAuthors', name: "list_authors")]
    public function listAuthors(AuthorRepository $repo)
    {

        $authors =  $repo->findAll();

        return $this->render("author/listAuthors.html.twig", ["list" => $authors]);
    }



    #[Route('/add', name: "add_author")]
    public function addAuthor(Request $req,  ManagerRegistry $manager)
    {
        $em =  $manager->getManager();
        $newAuthor = new Author();
        $newAuthor->setUsername("william");
        $newAuthor->setEmail("william@email.com");
        $em->persist($newAuthor);
        $em->flush();
        return $this->redirectToRoute("list_authors");
    }




    #[Route('/addAuthor', name: "add")]
    public function add(ManagerRegistry $manager, Request $req)

    {
        $em = $manager->getManager();
        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($req);
        if ($form->isSubmitted()) {
            $em->persist($author);
            $em->flush();
            return $this->redirectToRoute("list_authors");
        }
        return $this->render("author/add.html.twig", ["f" => $form]);
    }



    #[Route('/update/{id}', name: "update")]
    public function update(ManagerRegistry $manager, Request $req, $id, AuthorRepository $repo)
    {
        $em = $manager->getManager();
        $author = $repo->find($id);
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($req);

        if ($form->isSubmitted()) {
            $em->persist($author);
            $em->flush();
            return $this->redirectToRoute("list_authors");
        }
        return $this->render("author/update.html.twig", ["f" => $form]);
    }

    #[Route('/delete/{id}', name: "delete")]
    public function delete($id, ManagerRegistry $manager, AuthorRepository $repo)
    {
        $em = $manager->getManager();
        $author = $repo->find($id);


        if ($author->getNbBooks() == 0) {

            $em->remove($author);
            $em->flush();
        } else {

            return $this->render("author/errorDelete.html.twig");
        }

        return $this->redirectToRoute("list_authors");
    }
}
