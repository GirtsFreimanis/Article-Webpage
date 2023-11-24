<?php

declare(strict_types=1);

namespace App\Controllers;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Dotenv\Dotenv;

abstract class BaseController
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
}