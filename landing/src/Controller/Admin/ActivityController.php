<?php

/*
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 */

namespace App\Controller\Admin;

use App\Service\ActivityService;
use App\Service\Exception\JsonRpcErrorException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActivityController extends AbstractController
{
    #[Route('/admin/activity', name: 'app_admin_activity')]
    public function index(Request $request, ActivityService $service): Response
    {
        $error = null;
        $currentPage = $request->get('page') ?? 1;

        try {
            list($pages, $items) = $service->getItems($currentPage);
        } catch (JsonRpcErrorException $e) {
            $error = $e->getMessage();
            $pages = 0;
            $items = [];
        }

        return $this->render('admin/activity/index.html.twig', [
            'controller_name' => 'ActivityController',
            'currentPage' => $currentPage,
            'pages' => $pages,
            'items' => $items,
            'error' => $error,
        ]);
    }
}
