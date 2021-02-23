<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VilleController extends AbstractController
{
    /**
     * @Route("/admin/ville", name="ville_index")
     */
    public function index(): Response
    {
        return $this->render('ville/index.html.twig', [
            'controller_name' => 'VilleController',
        ]);
    }
}
