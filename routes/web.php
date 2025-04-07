<?php

use app\controllers\HomeController;
use app\controllers\LoginController;
use app\controllers\ProductController;
use app\controllers\UserController;
use core\library\Router;

$router = $app->container->get(Router::class);
//GET
$router->add('GET', '/', [HomeController::class, 'index']);
$router->add('GET', '/users', [UserController::class, 'index']);

//POST
$router->add('POST', '/create/user', [UserController::class, 'store']);
$router->add('POST', '/user', [UserController::class, 'show']);
$router->add('POST', '/login', [UserController::class, 'authenticate']);


$router->execute();
