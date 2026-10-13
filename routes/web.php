<?php

use App\Core\Router;

$router = new Router();
$router->dispatch($_SERVER['REQUEST_URI'] ?? '/');
