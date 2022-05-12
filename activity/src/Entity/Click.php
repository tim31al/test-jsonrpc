<?php

declare(strict_types=1);

/*
 *
 * (c) Alexandr Timofeev <tim31al@gmail.com>
 *
 */

namespace App\Entity;

use App\Repository\ClickRepository;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\ArrayShape;

#[ORM\Entity(repositoryClass: ClickRepository::class)]
#[ORM\Table(name: 'activity_clicks')]
#[ORM\Index(columns: ['url'], name: 'activity_url_idx')]
class Click implements \JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private string $url;

    #[ORM\Column(type: 'integer', options: [
        'default' => 0,
    ])]
    private int $counter = 0;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $lastVisit;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getCounter(): ?int
    {
        return $this->counter;
    }

    public function setCounter(int $counter): self
    {
        $this->counter = $counter;

        return $this;
    }

    public function getLastVisit(): ?\DateTimeInterface
    {
        return $this->lastVisit;
    }

    public function setLastVisit(\DateTimeInterface $lastVisit): self
    {
        $this->lastVisit = $lastVisit;

        return $this;
    }

    public function inc(): self
    {
        $this->counter = $this->counter + 1;

        return $this;
    }

    #[ArrayShape(['id' => 'int', 'url' => 'string', 'counter' => 'int', 'lastVisit' => "\DateTimeInterface"])]
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'url' => $this->url,
            'counter' => $this->counter,
            'lastVisit' => $this->lastVisit->format('Y-m-d H:i:s'),
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
