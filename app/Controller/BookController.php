<?php

namespace Library\PHP\MVC\Controller;

use Library\PHP\MVC\App\View;
use Library\PHP\MVC\Config\Database;
use Library\PHP\MVC\Model\BorrowingRequest;
use Library\PHP\MVC\Repository\BookRepository;
use Library\PHP\MVC\Repository\SessionRepository;
use Library\PHP\MVC\Repository\UserRepository;
use Library\PHP\MVC\Service\BookService;
use Library\PHP\MVC\Model\BookRequest;
use Library\PHP\MVC\Model\BookUpdateRequest;
use Library\PHP\MVC\Exception\ValidationException;
use Library\PHP\MVC\Service\SessionService;
use Library\PHP\MVC\Service\UserService;

class BookController
{
    private BookService $bookService;
    private UserService $userService;
    private SessionService $sessionService;


    public function __construct()
    {
        $connection = Database::getConnection();

        $bookRepository = new BookRepository($connection);
        $userRepository = new UserRepository($connection);
        $sessionRepository = new SessionRepository($connection);

        $this->bookService = new BookService($bookRepository);
        $this->userService = new UserService($userRepository);
        $this->sessionService = new SessionService($sessionRepository, $userRepository);

    }

    public function listBooks(): void
    {
        try {
            $user = $this->sessionService->current();
            $books = $this->bookService->getAllBooks();
            View::renderNew('Book/list', [
                'title' => 'All Books',
                'books' => $books,
                "user" => [
                    "name" => $user->name ?? 'Guest'
                ]
            ]);
        } catch (\Exception $e) {
            View::renderNew('Book/list', [
                'error' => $e->getMessage()
            ]);
        }
    }

    public function viewBook(string $bookId): void
    {
        try {
            $user = $this->sessionService->current();
            $book = $this->bookService->getBookById($bookId);
            if ($book) {
                View::renderNew('Book/view', [
                    'title' => 'View Book',
                    'book' => $book,
                    "user" => [
                        "name" => $user->name ?? 'Guest'
                    ]
                ]);
            } else {
                View::renderNew('Book/view', [
                    'title' => 'View Book',
                    'error' => 'Book not found.',
                    "user" => [
                        "name" => $user->name ?? 'Guest'
                    ]
                ]);
            }
        } catch (\Exception $e) {
            View::renderNew('Book/view', [
                'title' => 'View Book',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function addBook(): void
    {
        $user = $this->sessionService->current();
        View::renderNew('Book/add', [
            'title' => 'Add a new Book',
            'header' => 'Adding a new Book',
            "user" => [
                "name" => $user->name ?? 'Guest'
            ]
        ]);
    }

    public function postAddBook(): void
    {
        $request = new BookRequest();
        $request->title = $_POST['title'];
        $request->author = $_POST['author'];
        $request->publicationYear = $_POST['publicationYear'];
        $request->available = $_POST['available'] ?? true;

        try {
            $this->bookService->addBook($request);
            View::redirect('/books');
        } catch (ValidationException $exception) {
            View::renderNew('Book/add', [
                'title' => 'Add a new Book',
                'error' => $exception->getMessage()
            ]);
        } catch (\Exception $e) {
            View::renderNew('Book/add', [
                'title' => 'Add a new Book',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function deleteBook(string $bookId): void
    {
        try {
            $this->bookService->deleteBook($bookId);
            View::redirect('/books');

        } catch (\Exception $e) {
            View::redirectError('/books', $e->getMessage());
        }
    }

    public function updateBook(string $bookId): void
    {
        try {
            $book = $this->bookService->getBookById($bookId);
            $user = $this->sessionService->current();

            // Check if the book is found
            if ($book) {
                View::renderNew('Book/edit', [
                    'title' => 'Update Book',
                    'book' => $book,
                    'user' => [
                        'name' => $user->name
                    ]
                ]);
            } else {
                // Provide an empty book object or array if not found
//                View::renderNew('Book/edit', [
//                    'title' => 'View Book',
//                    'error' => 'Book not found.',
//                    'book' => (object)[] // Safe empty object for view
//                ]);
                View::redirectError('/books', 'Book not found.');
            }
        } catch (\Exception $e) {
//            View::renderNew('Book/edit', [
//                'error' => $e->getMessage(),
//                'book' => (object)[] // Ensure book is always set, even in error
//            ]);
            View::redirectError('/books', $e->getMessage());
        }
    }

    public function postUpdateBook(): void
    {
        $request = new BookUpdateRequest();
        $request->id = $_POST['id'];
        $request->author = $_POST['author'];
        $request->title = $_POST['title'];
        $request->publicationYear = $_POST['publicationYear'];
        $request->available = $_POST['available'];
//        print_r($_POST);
//        print_r($request);
        try {
            $this->bookService->updateBook($request);
            View::redirect('/books');
        } catch (ValidationException $exception) {
            View::renderNew('Book/edit', [
                'title' => 'Update Book',
                'error' => $exception->getMessage(),
                'book' => $request
            ]);
        } catch (\Exception $e) {
            View::renderNew('Book/edit', [
                'title' => 'Update Book',
                'error' => $e->getMessage(),
                'book' => $request
            ]);
        }
    }


}
