<?php

declare(strict_types=1);

namespace App\Services\Article;

use App\Models\Article;
use App\Repositories\ArticleRepository;

class StoreArticleService
{
    private ArticleRepository $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function execute(
        string $title,
        string $description,
        string $picture
    ): void
    {
        $article = new Article(
            $title,
            $description,
            $picture,
        );
        $this->articleRepository->insert($article);
    }
}