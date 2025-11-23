<?php

namespace Library\PHP\MVC\Service;

use Exception;
use Library\PHP\MVC\Config\Database;
use Library\PHP\MVC\Domain\Book;
use Library\PHP\MVC\Model\BookRequest;
use Library\PHP\MVC\Model\BookResponse;
use Library\PHP\MVC\Model\BookUpdateRequest;
use Library\PHP\MVC\Repository\BookRepository;
use PDOException;
use Throwable;

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
        } catch (PDOException $e) {
            throw new Exception("Error fetching book #{$bookId}", 0, $e);
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
        } catch (PDOException $e) {
            throw new Exception("Error fetching all books", 0, $e);
        }
    }

    /**
     * Add a new book.
     * @param BookRequest $bookRequest
     * @return BookResponse
     * @throws Exception
     * @throws Throwable
     */
    public function addBook(BookRequest $bookRequest): BookResponse
    {
        try {
            Database::beginTransaction();
            $book = new Book();
            $book->id = uniqid();
            $book->title = $bookRequest->title;
            $book->author = $bookRequest->author;
            $book->publicationYear = $bookRequest->publicationYear;
            $book->available = $bookRequest->available;
            $this->bookRepository->insertBook($book);

            $response = new BookResponse();
            $response->book = $book;

            Database::commitTransaction();
            return $response;
        } catch (PDOException $e) {
            Database::rollBackTransaction();
            throw new Exception("Error adding book '{$bookRequest->title}'", 0, $e);
        } catch (Throwable $e) {
            Database::rollBackTransaction();
            throw $e;
        }
    }

    /**
     * Delete a book by its ID.
     * @param string $bookId
     * @return bool
     * @throws Exception
     * @throws Throwable
     */
    public function deleteBook(string $bookId): bool
    {
        try {
            Database::beginTransaction();

            $deleted = $this->bookRepository->deleteBookById($bookId);

            Database::commitTransaction();
            return $deleted;

        } catch (PDOException $e) {
            Database::rollbackTransaction();
            throw new Exception("Error deleting book #{$bookId}", 0, $e);
        } catch (Throwable $e) {
            Database::rollbackTransaction();
            throw $e;
        }
    }

    /**
     * Update an existing book.
     * @param BookUpdateRequest $bookUpdateRequest
     * @return Book
     * @throws Exception
     * @throws Throwable
     */
    public function updateBook(BookUpdateRequest $bookUpdateRequest): Book
    {
        try {
            Database::beginTransaction();
            // Create a Book object based on the BookUpdateRequest
            $book = new Book();
            $book->id = $bookUpdateRequest->id;
            $book->title = $bookUpdateRequest->title;
            $book->author = $bookUpdateRequest->author;
            $book->publicationYear = $bookUpdateRequest->publicationYear;
            $book->available = $bookUpdateRequest->available;

            $updated = $this->bookRepository->updateBook($book);
            Database::commitTransaction();
            return $updated;
        } catch (PDOException $e) {
            Database::rollbackTransaction();
            throw new Exception("Error updating book #{$bookUpdateRequest->id}", 0, $e);
        } catch (Throwable $e) {
            Database::rollbackTransaction();
            throw $e;
        }
    }
}

