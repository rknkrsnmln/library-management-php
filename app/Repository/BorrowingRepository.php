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
        $statement = $this->connection->prepare("INSERT INTO borrowings (id, user_id, book_id, borrow_date, return_date, returned) 
            VALUES (?,?, ?, ?,?, ?)");
        $statement->execute([
            $borrowing->id,
            $borrowing->userId,
            $borrowing->bookId,
            $borrowing->borrowDate,
            $borrowing->returnDate,
            $borrowing->returned
        ]);
        return $borrowing;
    }

    public function update(Borrowing $borrowing): void
    {
        $query = "UPDATE borrowings 
                  SET user_id = :user_id, book_id = :book_id, borrow_date = :borrow_date, 
                      return_date = :return_date, returned = :returned 
                  WHERE id = :id";

        $stmt = $this->connection->prepare($query);

        $stmt->bindParam(':id', $borrowing->id);
        $stmt->bindParam(':user_id', $borrowing->userId);
        $stmt->bindParam(':book_id', $borrowing->bookId);
        $stmt->bindParam(':borrow_date', $borrowing->borrowDate);
        $stmt->bindParam(':return_date', $borrowing->returnDate);
        $stmt->bindParam(':returned', $borrowing->returned, PDO::PARAM_BOOL);

        $stmt->execute();
    }

    public function delete(string $id): void
    {
        $query = "DELETE FROM borrowings WHERE id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->execute([$id]);
    }

    public function findById(string $id): ?Borrowing
    {
        $query = "SELECT * FROM borrowings WHERE id = :id";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $returnedBorrowing = new Borrowing();
            $returnedBorrowing->id = $result["id"];
            $returnedBorrowing->userId = $result["user_id"];
            $returnedBorrowing->bookId = $result["book_id"];
            $returnedBorrowing->borrowDate = $result["borrow_date"];
            $returnedBorrowing->returnDate = $result["return_date"];
            $returnedBorrowing->returned = $result["returned"];
            return $returnedBorrowing;
        }

        return null;
    }

    public function findAll(): array
    {
        $query = "SELECT * FROM borrowings";
        $stmt = $this->connection->query($query);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $borrowings = [];
        foreach ($results as $result) {
            $borrowing = new Borrowing();
            $borrowing->id = $result['id'];
            $borrowing->userId = $result['user_id'];
            $borrowing->bookId = $result['book_id'];
            $borrowing->borrowDate = $result['borrow_date'];
            $borrowing->returnDate = $result['return_date'];
            $borrowing->returned = $result['returned'];
            $borrowings[] = $borrowing;
        }

        return $borrowings;
    }

}