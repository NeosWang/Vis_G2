<?php

$request = $_SERVER['REQUEST_URI'];

switch ($request) {
    case '/' :
        require __DIR__ . '/views/home.php';
        break;
    case '/wk2ex4b':
        require __DIR__ . '/views/wk2ex4b.php';
        break;
    case '/wk2ex4c':
        require __DIR__ . '/views/wk2ex4c.php';
        break;
    case '' :
        require __DIR__ . '/views/index.php';
        break;
    case '/about' :
        require __DIR__ . '/views/about.php';
        break;
    default:
        http_response_code(404);
        require __DIR__ . '/views/404.html';
        break;
}