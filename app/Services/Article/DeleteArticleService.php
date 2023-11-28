<?php

declare(strict_types=1);

namespace App\Services\Article;

use App\Repositories\ArticleRepository;

class DeleteArticleService
{
    private ArticleRepository $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function execute(int $id): void
    {
        $article = $this->articleRepository->getById($id);
        if ($article === null) {
            //throw exception
            return;
        }
        $this->articleRepository->delete($article);

    }
}