<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/appointements', name: 'appointements_user')]
class AppointementsUserController extends AbstractController
{
    #[Route('/appointements', name: 'appointements_user')]
    public function index(): Response
    {
        return $this->render('appointements_user/index.html.twig', [
            'controller_name' => 'AppointementsUserController',
        ]);
    }
}
