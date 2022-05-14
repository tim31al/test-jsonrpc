<?php

/*
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 */

namespace App\Controller;

use App\Repository\NewsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewsController extends AbstractClickController
{
    #[Route('/news', name: 'app_news')]
    public function index(Request $request, NewsRepository $repository): Response
    {
        $this->dispatchClickMessage($request);

        $news = $repository->findBy([], ['date' => 'desc']);

        return $this->render('news/index.html.twig', [
            'news' => $news,
        ]);
    }

    #[Route('/news/{slug}', name: 'app_news_item')]
    public function show(Request $request, NewsRepository $repository, string $slug): Response
    {
        $this->dispatchClickMessage($request);

        $news = $repository->findOneBy(['slug' => $slug]);

        return $this->render('news/show.html.twig', [
            'news' => $news,
        ]);
    }
}
