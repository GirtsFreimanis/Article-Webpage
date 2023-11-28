<?php

declare(strict_types=1);

namespace App\Controllers;

use App\RedirectResponse;
use App\Repositories\EmptyArticleRepository;
use App\Repositories\MysqlArticleRepository;
use App\Response;
use App\Services\Article\DeleteArticleService;
use App\Services\Article\IndexArticleService;
use App\Services\Article\ShowArticleService;
use App\Services\Article\StoreArticleService;
use App\Services\Article\UpdateArticleService;
use App\ViewResponse;
use App\Models\Article;
use Respect\Validation\Validator as v;

class ArticleController extends BaseController
{
    public function index(): Response
    {
        $message = $_SESSION['message'] ?? null;
        unset($_SESSION['message']);
        $status = $_SESSION['status'] ?? null;
        unset($_SESSION['status']);

        $service = new IndexArticleService(new MysqlArticleRepository());
        $articles = $service->execute();

        return new ViewResponse('articles/index', [
            'articles' => $articles,
            'status' => $status,
            'message' => $message
        ]);
    }

    public function show(string $id): Response
    {
        $service = new ShowArticleService(new MysqlArticleRepository());
        $article = $service->execute((int)$id);

        return new ViewResponse(
            "articles/show",
            ["article" => $article]
        );
    }

    public function create(): Response
    {
        return new ViewResponse(
            "articles/create"
        );
    }

    public function store(): Response
    {
        if ($this->validateArticle($_POST["title"], $_POST["description"]) === "error") {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Article not created. Invalid data given';
            return new RedirectResponse("/articles");
        }
        if ($_POST["picture"] === "") {
            $picture = "https://seeklogo.com/images/G/globe-logo-42DE548AC7-seeklogo.com.png";
        } else {
            $picture = $_POST["picture"];
        }

        $service = new StoreArticleService(new MysqlArticleRepository());
        $service->execute(
            $_POST['title'],
            $_POST['description'],
            $picture,
        );

        $_SESSION['status'] = 'success';
        $_SESSION['message'] = 'Article created successfully';

        return new RedirectResponse(
            "/articles"
        );
    }

    public function edit(string $id): Response
    {

        $data = $this->database->createQueryBuilder()
            ->select("*")
            ->from("articles")
            ->where("id = :id")
            ->setParameter("id", $id)
            ->fetchAssociative();

        $article = $this->buildModel($data);
        return new ViewResponse(
            "articles/edit",
            ["article" => $article]
        );
    }

    public function update(string $id): Response
    {
        if ($this->validateArticle($_POST["title"], $_POST["description"]) === "error") {
            $_SESSION['status'] = 'error';
            $_SESSION['message'] = 'Article not updated. Invalid data given';
            return new RedirectResponse("/articles");
        }

        if ($_POST["picture"] === "") {
            $picture = "https://seeklogo.com/images/G/globe-logo-42DE548AC7-seeklogo.com.png";
        } else {
            $picture = $_POST["picture"];
        }

        $service = new UpdateArticleService(new MysqlArticleRepository());
        $service->execute(
            (int)$id,
            $_POST['title'],
            $_POST['description'],
            $picture,
        );

        $_SESSION['status'] = 'success';
        $_SESSION['message'] = 'Article updated successfully';

        return new RedirectResponse("/articles");
    }

    public function delete(string $id): Response
    {
        $service = new DeleteArticleService(new MysqlArticleRepository());
        $service->execute((int)$id);

        $_SESSION['status'] = 'success';
        $_SESSION['message'] = 'Article deleted successfully';

        return new RedirectResponse("/articles");
    }

    public function buildModel(array $data): Article
    {
        return new Article(
            $data["title"],
            $data["description"],
            $data["picture"],
            $data["created_at"],
            (int)$data["id"],
            $data["updated_at"]
        );
    }

    public function validateArticle(?string $title, ?string $description): string
    {
        if (
            v::notBlank()->validate($title) &&
            v::notBlank()->validate($description)
        ) {
            return "success";
        }
        return "error";
    }
}