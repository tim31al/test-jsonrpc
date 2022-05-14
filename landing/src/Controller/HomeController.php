<?php

/*
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 */

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractClickController
{
    #[Route('/', name: 'homepage')]
    public function index(Request $request): Response
    {
        $this->dispatchClickMessage($request);

        return $this->render('home/index.html.twig');
    }
}
