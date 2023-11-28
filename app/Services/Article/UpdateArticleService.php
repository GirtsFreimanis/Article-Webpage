<?php

declare(strict_types=1);

namespace App\Services\Article;

use App\Repositories\ArticleRepository;

class UpdateArticleService
{
    private ArticleRepository $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function execute(
        int    $id,
        string $title,
        string $description,
        string $picture
    ): void
    {
        $article = $this->articleRepository->getById($id);
        if ($article === null) {
            //exception
            return;
        }
        $article->update([
            'title' => $title,
            'description' => $description,
            'picture' => $picture,
        ]);
        $this->articleRepository->update($article);
    }
}