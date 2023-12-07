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
            new Article("zinas", "desc", "", null, 1),
            new Article("news", "desc", "", null, 2),
            new Article("something", "desc", "", null, 3),
        ]);
    }

    public function getById(int $id): ?Article
    {
        $articles = $this->getAll()->getAll();
        
        foreach ($articles as $article) {
            if ($article->getId() === $id) {
                return $article;
            }
        }
        return null;
    }

    public function show(int $id): ?Article
    {
        return $this->getById($id);
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