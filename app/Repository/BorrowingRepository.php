<?php

namespace Library\PHP\MVC\Repository;

use Library\PHP\MVC\Domain\Borrowing;
use PDO;

class BorrowingRepository
{
    private \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function create(Borrowing $borrowing): Borrowing
    {
        $statement = $this->connection->prepare(
            "INSERT INTO borrowings (
            id, user_id, book_id, borrow_date, return_date, returned
        ) VALUES (
            :id, :user_id, :book_id, :borrow_date, :return_date, :returned
        )"
        );

        $statement->execute([
            ':id' => $borrowing->id,
            ':user_id' => $borrowing->userId,
            ':book_id' => $borrowing->bookId,
            ':borrow_date' => $borrowing->borrowDate,
            ':return_date' => $borrowing->returnDate,
            ':returned' => $borrowing->returned,
        ]);

        return $borrowing;
    }

    public function update(Borrowing $borrowing): void
    {
        $statement = $this->connection->prepare(
            "UPDATE borrowings
         SET user_id = :user_id,
             book_id = :book_id,
             borrow_date = :borrow_date,
             return_date = :return_date,
             returned = :returned
         WHERE id = :id"
        );

        $statement->execute([
            ':id' => $borrowing->id,
            ':user_id' => $borrowing->userId,
            ':book_id' => $borrowing->bookId,
            ':borrow_date' => $borrowing->borrowDate,
            ':return_date' => $borrowing->returnDate,
            ':returned' => $borrowing->returned,
        ]);
    }

    public function deleteById(string $id): void
    {
        $query = "DELETE FROM borrowings WHERE id = ?";
        $statement = $this->connection->prepare($query);
        $statement->execute([$id]);
    }

    public function findById(string $id): ?Borrowing
    {
        $statement = $this->connection->prepare(
            "SELECT * FROM borrowings WHERE id = :id"
        );

        // Execute using named placeholder array
        $statement->execute([':id' => $id]);

        try {
            if ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                return $this->getBorrowing($row);
            } else {
                return null;
            }
        } finally {
            $statement->closeCursor();
        }
    }

    public function findAll(): array
    {
        $statement = $this->connection->prepare("SELECT * FROM borrowings");
        $statement->execute();

        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        $borrowings = [];
        foreach ($rows as $row) {
            $borrowings[] = $this->getBorrowing($row);
        }

        return $borrowings;
    }

    private function getBorrowing(array $row): Borrowing
    {
        $borrowing = new Borrowing();
        $borrowing->id = $row["id"];
        $borrowing->userId = $row["user_id"];
        $borrowing->bookId = $row["book_id"];
        $borrowing->borrowDate = $row["borrow_date"];
        $borrowing->returnDate = $row["return_date"];
        $borrowing->returned = $row["returned"];
        return $borrowing;
    }

}