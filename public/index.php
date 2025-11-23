<?php

use Library\PHP\MVC\App\Router;
use Library\PHP\MVC\Config\Database;
use Library\PHP\MVC\Controller\BorrowingController;
use Library\PHP\MVC\Controller\HomeController;
use Library\PHP\MVC\Controller\UserController;
use Library\PHP\MVC\Controller\BookController;
use Library\PHP\MVC\Middleware\MustBeAdmin;
use Library\PHP\MVC\Middleware\MustNotBeLoggedInMiddleware;
use Library\PHP\MVC\Middleware\MustBeLoggedInMiddleware;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . "/../app/Bootstrap/error-handler.php";

initializeUsers();

// Connect to the database
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
Router::add('GET', '/books/add', BookController::class, 'addBook', [MustBeLoggedInMiddleware::class, MustBeAdmin::class]);
Router::add('POST', '/books/add', BookController::class, 'postAddBook', [MustBeLoggedInMiddleware::class, MustBeAdmin::class]);
Router::add('GET', '/books/view/([a-zA-Z0-9\s-]+)', BookController::class, 'viewBook', []);
Router::add('GET', '/books/update/([a-zA-Z0-9\s-]+)', BookController::class, 'updateBook', [MustBeLoggedInMiddleware::class, MustBeAdmin::class]);
Router::add('POST', '/books/update', BookController::class, 'postUpdateBook', [MustBeLoggedInMiddleware::class, MustBeAdmin::class]);
Router::add('GET', '/books/delete/([a-zA-Z0-9\s-]+)', BookController::class, 'deleteBook', [MustBeLoggedInMiddleware::class, MustBeAdmin::class]);
Router::add('GET', '/borrowings', BorrowingController::class, 'borrowingList', []);
Router::add('GET', '/borrowings/create', BorrowingController::class, 'borrowingBook', [MustBeLoggedInMiddleware::class]);
Router::add('POST', '/borrowings/create', BorrowingController::class, 'postBorrowingBook', [MustBeLoggedInMiddleware::class]);
Router::add('GET', '/borrowings/delete/([a-zA-Z0-9\s-]+)', BorrowingController::class, 'deleteBorrowing', [MustBeLoggedInMiddleware::class]);
Router::add('GET', '/borrowings/update/([a-zA-Z0-9\s-]+)', BorrowingController::class, 'updateBorrowing', [MustBeLoggedInMiddleware::class]);
Router::add('POST', '/borrowings/update', BorrowingController::class, 'postUpdateBorrowing', [MustBeLoggedInMiddleware::class]);
Router::add('GET', '/500', HomeController::class, 'fiveHundredResponse', []);

Router::run();


function initializeUsers(): void
{
    try {
        $pdo = Database::getConnection('test');

        // Check if users table has any record
        $stmt = $pdo->query("SELECT COUNT(*) FROM users");
        $userCount = (int)$stmt->fetchColumn();

        if ($userCount === 0) {

            // Create default admin & member
            Database::createUser(
                'admin_id',
                'Admin User',
                'adminpassword',
                'active',
                'admin'
            );
            Database::createUser(
                'member_id',
                'Member User',
                'memberpassword',
                'active',
                'member'
            );

            // Log informational message
            error_log("[INIT] Default users created.");
        } else {
            // Log informational message
            error_log("[INIT] Users already exist. Initialization skipped.");
        }

    } catch (Throwable $e) {
        // Let the global handler take over
        throw $e;
    }
}