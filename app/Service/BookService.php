<?php

namespace Library\PHP\MVC\Service;

use Exception;
use Library\PHP\MVC\Domain\Book;
use Library\PHP\MVC\Model\BookRequest;
use Library\PHP\MVC\Model\BookUpdateRequest;
use Library\PHP\MVC\Repository\BookRepository;

class BookService
{
    private BookRepository $bookRepository;

    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    /**
     * Get a book by its ID.
     * @param string $bookId
     * @return Book|null
     * @throws Exception
     */
    public function getBookById(string $bookId): ?Book
    {
        try {
            return $this->bookRepository->findBookById($bookId);
        } catch (\PDOException $e) {
            throw new Exception("An error occurred while fetching the book: " . $e->getMessage());
        }
    }

    /**
     * Get all books.
     * @return Book[]
     * @throws Exception
     */
    public function getAllBooks(): array
    {
        try {
            return $this->bookRepository->findAllBook();
        } catch (\PDOException $e) {
            throw new Exception("An error occurred while fetching all books: " . $e->getMessage());
        }
    }

    /**
     * Add a new book.
     * @param BookRequest $bookRequest
     * @return Book
     * @throws Exception
     */
    public function addBook(BookRequest $bookRequest): Book
    {
        $book = new Book();
        $book->id = uniqid();
        $book->title = $bookRequest->title;
        $book->author = $bookRequest->author;
        $book->publicationYear = $bookRequest->publicationYear;
        $book->available = $bookRequest->available;
        try {
            return $this->bookRepository->insertBook($book);
        } catch (\PDOException $e) {
            throw new Exception("An error occurred while adding the book: " . $e->getMessage());
        }
    }

    /**
     * Delete a book by its ID.
     * @param string $bookId
     * @return bool
     * @throws Exception
     */
    public function deleteBook(string $bookId): bool
    {
        try {
            return $this->bookRepository->deleteBook($bookId);
        } catch (\PDOException $e) {
            throw new Exception("An error occurred while deleting the book: " . $e->getMessage());
        }
    }

    /**
     * Update an existing book.
     * @param BookUpdateRequest $bookUpdateRequest
     * @return Book
     * @throws Exception
     */
    public function updateBook(BookUpdateRequest $bookUpdateRequest): Book
    {
        try {
            // Create a Book object based on the BookUpdateRequest
            $book = new Book();
            $book->id = $bookUpdateRequest->id;
            $book->title = $bookUpdateRequest->title;
            $book->author = $bookUpdateRequest->author;
            $book->publicationYear = $bookUpdateRequest->publicationYear;
            $book->available = $bookUpdateRequest->available;

            return $this->bookRepository->updateBook($book);
        } catch (\PDOException $e) {
            throw new Exception("An error occurred while updating the book: " . $e->getMessage());
        }
    }
}

