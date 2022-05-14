<?php

/*
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 */

namespace App\EntityListener;

use App\Entity\News;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class NewsEntityListener
{
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function prePersist(News $news, LifecycleEventArgs $event): void
    {
        $news->computeSlug($this->slugger);
    }

    public function preUpdate(News $news, LifecycleEventArgs $event): void
    {
        $news->computeSlug($this->slugger);
    }
}
