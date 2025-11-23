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
     * @return Book[]
     */
    public function findAllBook(): array
    {
        $statement = $this->connection->prepare("SELECT * FROM books");
        $statement->execute();

        $rows = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $books = [];
        foreach ($rows as $row) {
            $books[] = $this->getBook($row);
        }

        return $books;
    }

    /**
     * Find a book by its ID.
     */
    public function findBookById(string $bookId): ?Book
    {
        $statement = $this->connection->prepare(
            "SELECT * FROM books WHERE id = :id"
        );

        $statement->execute([':id' => $bookId]);

        try {
            if ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
                return $this->getBook($row);
            }
            return null;

        } finally {
            $statement->closeCursor();
        }
    }

    /**
     * Insert a new book into the database.
     */
    public function insertBook(Book $book): Book
    {
        $statement = $this->connection->prepare(
            "INSERT INTO books(
                id, title, author, publication_year, available
            ) VALUES (
                :id, :title, :author, :publication_year, :available
            )"
        );

        $statement->execute([
            ':id' => $book->id,
            ':title' => $book->title,
            ':author' => $book->author,
            ':publication_year' => $book->publicationYear,
            ':available' => $book->available,
        ]);

        return $book;
    }

    /**
     * Hydrate Book object from row
     */
    private function getBook(array $row): Book
    {
        $book = new Book();
        $book->id = $row['id'];
        $book->title = $row['title'];
        $book->author = $row['author'];
        $book->publicationYear = (int)$row['publication_year'];
        $book->available = (bool)$row['available'];

        return $book;
    }

    /**
     * Delete a book by its ID.
     */
    public function deleteBookById(string $bookId): bool
    {
        $statement = $this->connection->prepare(
            "DELETE FROM books WHERE id = :id"
        );

        $statement->execute([':id' => $bookId]);

        return $statement->rowCount() > 0;
    }

    /**
     * Update an existing book.
     */
    public function updateBook(Book $book): Book
    {
        $statement = $this->connection->prepare(
            "UPDATE books
             SET title = :title,
                 author = :author,
                 publication_year = :publication_year,
                 available = :available
             WHERE id = :id"
        );

        $statement->execute([
            ':title' => $book->title,
            ':author' => $book->author,
            ':publication_year' => $book->publicationYear,
            ':available' => $book->available,
            ':id' => $book->id,
        ]);

        return $book;
    }
}


