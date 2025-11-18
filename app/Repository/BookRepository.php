<?php

namespace Library\PHP\MVC\Repository;

use Library\PHP\MVC\Domain\Book;

class BookRepository
{
    private \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }


    /**
     * Find all books
     * @return Book[] Returns an array of Book objects
     */
    public function findAllBook(): array
    {
        $statement = $this->connection->prepare("SELECT * FROM books");
        $statement->execute();
        $booksData = $statement->fetchAll();
        $books = [];
        foreach ($booksData as $bookData) {
            $books[] = $this->getBook($bookData);
        }
        return $books;
    }

    /**
     * Find a book by its ID.
     * @param string $bookId
     * @return Book|null
     */
    public function findBookById(string $bookId): ?Book
    {
        $statement = $this->connection->prepare("SELECT * FROM books WHERE id = ?");
        $statement->execute([$bookId]);

        try {
            if ($row = $statement->fetch()) {
                return $this->getBook($row);
            } else {
                return null;
            }
        } finally {
            $statement->closeCursor();
        }
    }

    /**
     * Insert a new book into the database.
     * @param Book $book
     * @return Book The generated book
     */
    public function insertBook(Book $book): Book
    {
        $statement = $this->connection->prepare("
        INSERT INTO books(id, title, author, publication_year, available)
        values (?,?,?,?,?)");
        $statement->execute([
            $book->id,
            $book->title,
            $book->author,
            $book->publicationYear,
            $book->available
        ]);
        return $book;
    }

    /**
     * @param mixed $row
     * @return Book
     */
    private function getBook(array $row): Book
    {
        $book = new Book();
        $book->title = $row['title'];
        $book->author = $row['author'];
        $book->publicationYear = (int)$row['publication_year'];
        $book->available = (bool)$row['available'];
        $book->id = $row['id'];
        return $book;
    }

    /**
     * Delete a book by its ID.
     *
     * @param string $bookId
     * @return bool Returns true if the book was deleted, false otherwise
     */
    public function deleteBook(string $bookId): bool
    {
        $statement = $this->connection->prepare("DELETE FROM books WHERE id = ?");
        $statement->execute([$bookId]);
        $statement->closeCursor();
        return $statement->rowCount() > 0;
    }

    /**
     * Update an existing book in the database.
     *
     * @param Book $book
     * @return Book Returns the updated book
     */
    public function updateBook(Book $book): Book
    {
        $statement = $this->connection->prepare("UPDATE books 
                SET title = ?, author = ?, publication_year = ?, available = ? 
                WHERE id = ?");
        $statement->execute([
            $book->title,
            $book->author,
            $book->publicationYear,
            $book->available,
            $book->id
        ]);
//        return $this->getBook($book);
        return $book;
    }
}


