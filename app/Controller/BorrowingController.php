<?php

namespace Library\PHP\MVC\Controller;

use Library\PHP\MVC\App\View;
use Library\PHP\MVC\Config\Database;
use Library\PHP\MVC\Model\BorrowingRequest;
use Library\PHP\MVC\Model\BorrowingUpdateRequest;
use Library\PHP\MVC\Repository\BookRepository;
use Library\PHP\MVC\Repository\BorrowingRepository;
use Library\PHP\MVC\Repository\SessionRepository;
use Library\PHP\MVC\Repository\UserRepository;
use Library\PHP\MVC\Service\BookService;
use Library\PHP\MVC\Service\BorrowingService;
use Library\PHP\MVC\Service\SessionService;
use Library\PHP\MVC\Service\UserService;

class BorrowingController
{
    private BookService $bookService;
    private UserService $userService;
    private SessionService $sessionService;
    private BorrowingService $borrowingService;


    public function __construct()
    {
        $connection = Database::getConnection();
        $bookRepository = new BookRepository($connection);
        $userRepository = new UserRepository($connection);
        $sessionRepository = new SessionRepository($connection);
        $borrowingRepository = new BorrowingRepository($connection);

        $this->bookService = new BookService($bookRepository);
        $this->userService = new UserService($userRepository);
        $this->borrowingService = new BorrowingService($borrowingRepository);
        $this->sessionService = new SessionService($sessionRepository, $userRepository);
    }

    public function borrowingBook(): void
    {
        try {
            $users = $this->userService->findAll();
            $books = $this->bookService->getAllBooks();
            View::renderNew('Borrowing/create', [
                    'title' => 'Borrowing Book',
                    'books' => $books,
                    'users' => $users
                ]
            );
        } catch (\Exception $e) {
            View::renderNew('Borrowing/create', [
                    'title' => 'Borrowing Book',
                    'books' => [],
                    'users' => [],
                    'error' => $e->getMessage()
                ]
            );
        }
    }

    public function postBorrowingBook(): void
    {
        $request = new BorrowingRequest();
        $request->bookId = $_POST['book'];
        $request->userId = $_POST['user'];
        $request->borrowDate = $_POST['borrow_date'];
        $request->returnDate = $_POST['return_date'];
        $returned = isset($_POST['returned']) ? (int)$_POST['returned'] : 0;
        $request->returned = (int)$returned;
        print_r($request);
        try {
            $this->borrowingService->createBorrowing($request);
            View::redirect('/borrowings');
        } catch (\Exception $e) {
            View::redirectError('/borrowings/create', 'Please fill the form correctly');
        }

    }

    public function borrowingList(): void
    {
        try {
            $borrowings = $this->borrowingService->getAllBorrowings();
            if ($borrowings) {
                View::renderNew('Borrowing/list', [
                    'title' => 'All Of Lending List',
                    'borrowings' => $borrowings,
                    'user' => [
                        'name' => $user->name ?? 'Guest'
                    ]
                ]);
            } else {
                View::renderNew('Borrowing/list', [
                    'title' => 'All Of Lending List',
                    'borrowings' => [],
                    'user' => [
                        'name' => $user->name ?? 'Guest'
                    ]
                ]);
            }
        } catch (\Exception $e) {
            View::redirectError('Borrowing/list', $e->getMessage());
        }
    }

    public function updateBorrowing(string $borrowingId): void
    {
        try {
            $borrowing = $this->borrowingService->getBorrowingById($borrowingId);
//            $book = $this->bookService->getBookById($borrowing->bookId);
//            $user = $this->userService->findById($borrowing->userId);
            print_r($borrowing);
            if ($borrowing) {
                View::renderNew('Borrowing/edit', [
                    'title' => 'Update Lending Information',
                    'borrowing' => $borrowing,
                ]);
            } else {
                View::renderNew('Borrowing/edit', [
                    'title' => 'Update Lending Information',
                    'error' => 'Lending info not found.'
                ]);
            }
        } catch (\Exception $e) {
            View::renderNew('Borrowing/edit', [
                'error' => $e->getMessage()
            ]);
        }
    }

    public function postUpdateBorrowing(): void
    {
        $request = new BorrowingUpdateRequest();
        $request->id = $_POST['id'];
        $request->bookId = $_POST['bookId'];
        $request->borrowDate = $_POST['borrow_date'];
        $request->returnDate = $_POST['return_date'];
        $request->userId = $_POST['userId'];
        $returned = isset($_POST['returned']) ? (int)$_POST['returned'] : 0;
        $request->returned = (int)$returned;
//        print_r($request);
        try {
            $this->borrowingService->updateBorrowing($request);
            View::redirect('/borrowings');
        } catch (\Exception $e) {
        }
    }

    public function deleteBorrowing(string $borrowingId): void
    {
        try {
            $this->borrowingService->deleteBorrowing($borrowingId);
            View::redirect('/borrowings');

        } catch (\Exception $e) {
            View::redirectError('/borrowings', $e->getMessage());
        }
    }

}