<?php

declare(strict_types=1);

use App\RedirectResponse;
use App\Repositories\ArticleRepository;
use App\Repositories\MysqlArticleRepository;
use App\ViewResponse;
use DI\ContainerBuilder;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

session_start();

require_once "../vendor/autoload.php";

$loader = new FilesystemLoader('../Views');
$twig = new Environment($loader);

$builder = new ContainerBuilder();

$builder->addDefinitions([
    ArticleRepository::class => DI\create(MysqlArticleRepository::class)
]);

$container = $builder->build();

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/articles', ['App\Controllers\ArticleController', "index"]);
    $r->addRoute('GET', '/articles/{id:\d+}', ['App\Controllers\ArticleController', "show"]);

    $r->addRoute('GET', '/articles/create', ['App\Controllers\ArticleController', "create"]);
    $r->addRoute('POST', '/articles', ['App\Controllers\ArticleController', "store"]);

    $r->addRoute('GET', '/articles/{id:\d+}/edit', ['App\Controllers\ArticleController', "edit"]);
    $r->addRoute('POST', '/articles/{id:\d+}', ['App\Controllers\ArticleController', "update"]);

    $r->addRoute('POST', '/articles/delete/{id:\d+}', ['App\Controllers\ArticleController', "delete"]);
});
// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];

        [$controller, $method] = $handler;

        $controller = $container->get($controller);
        $response = $controller->{$method}(...array_values($vars));

        switch (true) {
            case $response instanceof ViewResponse:
                echo $twig->render($response->getViewName() . ".twig", $response->getData());
                break;
            case $response instanceof RedirectResponse:
                header("Location: " . $response->getLocation());
                break;
            default:
                //throw new exception
                break;
        }
        break;
}