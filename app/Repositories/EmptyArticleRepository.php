<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Collections\ArticleCollection;
use App\Models\Article;

class EmptyArticleRepository implements ArticleRepository
{
    public function getAll(): ArticleCollection
    {
        return new ArticleCollection([
            new Article("title1", "desc", "", null, 1),
            new Article("title2", "desc", "", null, 2),
            new Article("title3", "desc", "", null, 3),
        ]);
    }

    public function getById(int $id): ?Article
    {
        return null;
    }

    public function insert(Article $article): void
    {
        // TODO: Implement insert() method.
    }

    public function update(Article $article): void
    {
        // TODO: Implement update() method.
    }

    public function delete(Article $article): void
    {
        // TODO: Implement delete() method.
    }

    public function buildModel(array $data): Article
    {
        // TODO: Implement buildModel() method.
    }
}