<?php

/*
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 */

namespace App\Controller;

use App\Message\ClickMessage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;

abstract class AbstractClickController extends AbstractController
{
    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    /**
     * Обработчик кликов.
     */
    public function dispatchClickMessage(Request $request): void
    {
        $url = $request->getPathInfo();
        $this->messageBus->dispatch(new ClickMessage($url));
    }
}
