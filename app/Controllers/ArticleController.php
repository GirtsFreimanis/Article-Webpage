<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Collections\ArticleCollection;
use App\RedirectResponse;
use App\Response;
use App\ViewResponse;
use App\Models\Article;
use Carbon\Carbon;
use DateTimeZone;
use Respect\Validation\Validator as v;

class ArticleController extends BaseController
{
    public function index(): Response
    {
        $message = $_SESSION['message'] ?? null;
        unset($_SESSION['message']);
        $status = $_SESSION['status'] ?? null;
        unset($_SESSION['status']);

        $articles = $this->database->createQueryBuilder()
            ->select("*")
            ->from("articles")
            ->fetchAllAssociative();

        $articleCollection = new ArticleCollection();
        $articles = array_reverse($articles);

        foreach ($articles as $article) {
            $articleCollection->add(new Article(
                $article["title"],
                $article["description"],
                $article["picture"],
                $article["created_at"],
                (int)$article["id"],
                $article["updated_at"]
            ));
        }

        return new ViewResponse('articles/index', [
            'articles' => $articleCollection,
            'status' => $status,
            'message' => $message
        ]);
    }

    public function show(string $id): Response
    {
        $data = $this->database->createQueryBuilder()
            ->select("*")
            ->from("articles")
            ->where("id = :id")
            ->setParameter("id", $id)
            ->fetchAssociative();
        $article = new Article(
            $data["title"],
            $data["description"],
            $data["picture"],
            $data["created_at"],
            (int)$data["id"],
            $data["updated_at"]
        );
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

        $this->database->createQueryBuilder()
            ->insert("articles")
            ->values(
                [
                    "title" => ":title",
                    "description" => ":description",
                    "picture" => ":picture",
                    "created_at" => ":created_at",
                ]
            )->setParameters(
                [
                    "title" => $_POST["title"],
                    "description" => $_POST["description"],
                    "picture" => $picture,
                    "created_at" => Carbon::now(new DateTimeZone('Europe/Riga'))
                ]
            )->executeQuery();

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

        $article = new Article(
            $data["title"],
            $data["description"],
            $data["picture"],
            $data["created_at"],
            (int)$data["id"],
            $data["updated_at"]
        );
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

        $this->database->createQueryBuilder()
            ->update("articles")
            ->set("title", ":title")
            ->set("description", ":description")
            ->set("picture", ":picture")
            ->set("updated_at", ":updated_at")
            ->where("id = :id")
            ->setParameters([
                "id" => $id,
                "title" => $_POST["title"],
                "description" => $_POST["description"],
                "picture" => $picture,
                "updated_at" => Carbon::now(new DateTimeZone('Europe/Riga')),
            ])->executeQuery();

        $_SESSION['status'] = 'success';
        $_SESSION['message'] = 'Article updated successfully';

        return new RedirectResponse("/articles");
    }

    public function delete(string $id): Response
    {
        $this->database->createQueryBuilder()
            ->delete("articles")
            ->where("id = :id")
            ->setParameter("id", $id)
            ->executeQuery();
        $_SESSION['status'] = 'success';
        $_SESSION['message'] = 'Article deleted successfully';

        return new RedirectResponse("/articles");
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

    public function validatePictureUrl(?string $picture)
    {

    }
}