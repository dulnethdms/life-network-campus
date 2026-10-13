<?php

declare(strict_types=1);

$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/life-network-campus/public/login';

session_start();
require __DIR__ . '/../public/index.php';
