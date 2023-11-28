<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Collections\ArticleCollection;
use App\Models\Article;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Dotenv\Dotenv;

class MysqlArticleRepository implements ArticleRepository
{
    protected Connection $database;

    public function __construct()
    {
        $dotenv = Dotenv::createImmutable("../");
        $dotenv->load();

        $connectionParams = [
            'dbname' => $_ENV["DB_NAME"],
            'user' => $_ENV["DB_USER"],
            'password' => $_ENV["DB_USER_PASSWORD"],
            'host' => $_ENV["DB_HOST"],
            'driver' => 'pdo_mysql',
        ];
        $this->database = DriverManager::getConnection($connectionParams);
    }

    public function getById(int $id): ?Article
    {
        $data = $this->database->createQueryBuilder()
            ->select("*")
            ->from("articles")
            ->where("id = :id")
            ->setParameter("id", $id)
            ->fetchAssociative();

        if (empty($data)) {
            return null;
        }
        return $this->buildModel($data);
    }

    public function getAll(): ArticleCollection
    {
        $articles = $this->database->createQueryBuilder()
            ->select("*")
            ->from("articles")
            ->fetchAllAssociative();

        $articleCollection = new ArticleCollection();
        $articles = array_reverse($articles);

        foreach ($articles as $data) {
            $articleCollection->add(
                $this->buildModel($data)
            );
        }
        return $articleCollection;
    }

    public function insert(Article $article): void
    {
        $this->database->createQueryBuilder()
            ->insert('articles')
            ->values(
                [
                    "title" => ":title",
                    "description" => ":description",
                    "picture" => ":picture",
                    "created_at" => ":created_at",
                ]
            )->setParameters(
                [
                    "title" => $article->getTitle(),
                    "description" => $article->getDescription(),
                    "picture" => $article->getPicture(),
                    "created_at" => $article->getCreatedAt()
                ]
            )->executeQuery();
    }

    public function update(Article $article): void
    {
        $this->database->createQueryBuilder()
            ->update('articles')
            ->set('title', ':title')
            ->set('description', ':description')
            ->set('picture', ':picture')
            ->set('updated_at', ':updated_at')
            ->where('id = :id')
            ->setParameters([
                'id' => $article->getId(),
                'title' => $article->getTitle(),
                'description' => $article->getDescription(),
                'picture' => $article->getPicture(),
                'updated_at' => $article->getUpdatedAt()
            ])->executeQuery();
    }

    public function delete(Article $article): void
    {
        $this->database->createQueryBuilder()
            ->delete("articles")
            ->where("id = :id")
            ->setParameter("id", $article->getId())
            ->executeQuery();
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
}