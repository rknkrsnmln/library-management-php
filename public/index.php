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


// Connect to the database
Database::getConnection('test');

// Ensure users are created only once (check if the table is empty or the admin user exists)
initializeUsers();

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

/**
 * Initializes users (creates admin and member) if no users exist in the database.
 */
function initializeUsers(): void
{
    try {
        $pdo = Database::getConnection('test');
        // Check if users already exist by looking for any records in the users table
        $stmt = $pdo->query("SELECT COUNT(*) FROM users");
        $userCount = $stmt->fetchColumn();

        // Only create users if the table is empty
        if ($userCount == 0) {
            // Call the createUser method to create default users (admin and member)
            Database::createUser(
                'admin_id',       // Admin User ID
                'Admin User',     // Admin Name
                'adminpassword',  // Admin Password (plain text, will be hashed)
                'active',         // Status
                'admin'           // Role
            );
            Database::createUser(
                'member_id',      // Member User ID
                'Member User',    // Member Name
                'memberpassword', // Member Password (plain text, will be hashed)
                'active',         // Status
                'member'          // Role
            );
            echo "Default users created successfully!";
        } else {
            echo "Users already exist, skipping creation.";
        }
    } catch (Exception $e) {
        echo "Error while initializing users: " . $e->getMessage();
    }
}