<?php

use Library\PHP\MVC\App\Router;
use Library\PHP\MVC\Config\Database;
use Library\PHP\MVC\Controller\BorrowingController;
use Library\PHP\MVC\Controller\HomeController;
use Library\PHP\MVC\Controller\UserController;
use Library\PHP\MVC\Controller\BookController;
use Library\PHP\MVC\Middleware\MustNotBeLoggedInMiddleware;
use Library\PHP\MVC\Middleware\MustBeLoggedInMiddleware;

require_once __DIR__ . '/../vendor/autoload.php';


//Database::getConnection('prod');
Database::getConnection('test');

// Home Controller
Router::add('GET', '/', HomeController::class, 'index', []);

// User Controller
Router::add('GET', '/users/register', UserController::class, 'register', [MustNotBeLoggedInMiddleware::class]);
Router::add('POST', '/users/register', UserController::class, 'postRegister', [MustNotBeLoggedInMiddleware::class]);
Router::add('GET', '/users/login', UserController::class, 'login', [MustNotBeLoggedInMiddleware::class]);
Router::add('POST', '/users/login', UserController::class, 'postLogin', [MustNotBeLoggedInMiddleware::class]);
Router::add('GET', '/users/logout', UserController::class, 'logout', [MustBeLoggedInMiddleware::class]);
Router::add('GET', '/users/profile', UserController::class, 'updateProfile', [MustBeLoggedInMiddleware::class]);
Router::add('POST', '/users/profile', UserController::class, 'postUpdateProfile', [MustBeLoggedInMiddleware::class]);
Router::add('GET', '/users/password', UserController::class, 'updatePassword', [MustBeLoggedInMiddleware::class]);
Router::add('POST', '/users/password', UserController::class, 'postUpdatePassword', [MustBeLoggedInMiddleware::class]);
Router::add('GET', '/books', BookController::class, 'listBooks', []);
Router::add('GET', '/books/add', BookController::class, 'addBook', [MustBeLoggedInMiddleware::class]);
Router::add('POST', '/books/add', BookController::class, 'postAddBook', [MustBeLoggedInMiddleware::class]);
Router::add('GET', '/books/view/([a-zA-Z0-9\s-]+)', BookController::class, 'viewBook', []);
Router::add('GET', '/books/update/([a-zA-Z0-9\s-]+)', BookController::class, 'updateBook', [MustBeLoggedInMiddleware::class]);
Router::add('POST', '/books/update', BookController::class, 'postUpdateBook', [MustBeLoggedInMiddleware::class]);
Router::add('GET', '/books/delete/([a-zA-Z0-9\s-]+)', BookController::class, 'deleteBook', [MustBeLoggedInMiddleware::class]);
Router::add('GET', '/borrowings', BorrowingController::class, 'borrowingList', [MustBeLoggedInMiddleware::class]);
Router::add('GET', '/borrowings/create', BorrowingController::class, 'borrowingBook', [MustBeLoggedInMiddleware::class]);
Router::add('POST', '/borrowings/create', BorrowingController::class, 'postBorrowingBook', [MustBeLoggedInMiddleware::class]);
Router::add('GET','/borrowings/delete/([a-zA-Z0-9\s-]+)', BorrowingController::class, 'deleteBorrowing', [MustBeLoggedInMiddleware::class]);

Router::run();