<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use DateTimeZone;

class Article
{
    private string $title;
    private string $description;
    private string $picture;
    private Carbon $createdAt;

    private ?int $id;
    private ?Carbon $updatedAt;

    public function __construct(
        string  $title,
        string  $description,
        string  $picture,
        ?string $createdAt = null,
        ?int    $id = null,
        ?string $updatedAt = null
    )
    {
        $this->title = $title;
        $this->description = $description;
        $this->picture = $picture;
        $this->createdAt = $createdAt == null ? Carbon::now(new DateTimeZone('Europe/Riga')) : new Carbon($createdAt);
        $this->id = $id;
        $this->updatedAt = $updatedAt ? new Carbon($updatedAt) : null;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPicture(): string
    {
        return $this->picture;
    }

    public function getCreatedAt(): Carbon
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?Carbon
    {
        return $this->updatedAt;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function update(array $data)
    {
        $this->title = $data['title'] ?? $this->title;
        $this->description = $data['description'] ?? $this->description;
        $this->picture = $data['picture'] ?? $this->picture;
        $this->updatedAt = Carbon::now(new DateTimeZone('Europe/Riga'));
    }
}