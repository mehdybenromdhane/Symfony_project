<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BookController extends AbstractController
{





    #[Route('/addBook', name: "add_book")]
    public function addBook(ManagerRegistry $manager, Request $req)
    {


        $em = $manager->getManager();

        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($req);

        if ($form->isSubmitted()) {

            $nb = $book->getAuthor()->getNbBooks();

            $nb++;

            $book->getAuthor()->setNb_Books($nb);

            $book->setPublished(true);

            $em->persist($book);

            $em->flush();
        }
        return $this->render("book/addBook.html.twig", ['form' => $form]);
    }




    #[Route('/listbooks', name: "list_books")]
    public function listBooks(BookRepository $repo)
    {
        return $this->render("book/listBooks.html.twig", [
            "books" => $repo->showAllBooksByAuthor('William test')
        ]);
    }



    #[Route('/updateCat', name: "update_category")]
    public function updateCategory(BookRepository $repo, Request $req)
    {



        $old = $req->get('oldcategory');
        $new = $req->get('newcategory');

        $repo->updateCategory($old, $new);

        return $this->redirectToRoute('list_books');
    }


    #[Route('/updateBook/{idbook}', name: "update_book")]
    public function updateBook(BookRepository $repo, $idbook, ManagerRegistry $manager, Request $req)
    {


        $em = $manager->getManager();

        $book = $repo->find($idbook);
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($req);

        if ($form->isSubmitted()) {

            $em->persist($book);

            $em->flush();

            return $this->redirectToRoute("list_books");
        }

        return $this->render("book/updateBook.html.twig", ["form" => $form]);
    }


    #[Route('/deleteBook/{ref}', name: "delete_book")]
    public function deleteBook($ref, ManagerRegistry $manager, BookRepository $repo)
    {


        $book = $repo->find($ref);

        $em = $manager->getManager();

        $em->remove($book);
        $em->flush();


        return $this->redirectToRoute("list_books");
    }
}
