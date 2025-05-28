<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class IndexController extends AbstractController
{
    #[Route('/', name: 'index', host: 'book.d3vlab.org')]
    public function index(): Response
    {
        return $this->render('front/index.html.twig');
    }

    #[Route('/', name: 'homepage', host: 'd3vlab.org')]
    public function homepage(): Response
    {
        return $this->render('front/homepage.html.twig');
    }
}
