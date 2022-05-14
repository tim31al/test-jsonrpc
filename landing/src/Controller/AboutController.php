<?php

/*
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 */

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AboutController extends AbstractClickController
{
    #[Route('/about', name: 'app_about')]
    public function index(Request $request): Response
    {
        $this->dispatchClickMessage($request);

        return $this->render('about/index.html.twig');
    }
}
