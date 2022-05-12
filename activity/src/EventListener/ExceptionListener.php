<?php

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $code = $exception->getCode() ?: 500;

        $response = new JsonResponse(
            ['error' => $exception->getMessage()],
            $code
        );

        $event->setResponse($response);
    }
}
