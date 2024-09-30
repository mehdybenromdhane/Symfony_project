<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{



    #[Route('/product/{ide}', name: 'product_details')]
    public function index($ide): Response
    {



        return $this->render('product/list.html.twig', ['ide' => $ide]);
    }



    #[Route('/home', name: "app_home")]
    public function home(): Response
    {

        return $this->render('home.html.twig', []);
    }



    #[Route('/goToHome', name: "go_to_home")]
    public function test(): Response
    {

        return $this->redirectToRoute('app_home');
    }



    #[Route('/listProduct', name: "list_product")]
    public function listProudct(): Response
    {


        return $this->render('product/listProduct.html.twig');
    }
}
