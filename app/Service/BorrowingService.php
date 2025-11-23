<?php

namespace Library\PHP\MVC\Service;

use Exception;
use Library\PHP\MVC\Config\Database;
use Library\PHP\MVC\Domain\Borrowing;
use Library\PHP\MVC\Model\BorrowingRequest;
use Library\PHP\MVC\Model\BorrowingUpdateRequest;
use Library\PHP\MVC\Repository\BorrowingRepository;
use PDOException;
use Throwable;

class BorrowingService
{
    private BorrowingRepository $borrowingRepository;

    public function __construct(BorrowingRepository $borrowingRepository)
    {
        $this->borrowingRepository = $borrowingRepository;
    }

    /**
     * @throws Exception
     * @throws Throwable
     */
    public function createBorrowing(BorrowingRequest $request): Borrowing
    {
        // Basic validation
        if (empty($request->userId) || empty($request->bookId) || empty($request->borrowDate)) {
            throw new Exception("User ID, Book ID, and Borrow Date cannot be empty.");
        }

        try {
            Database::beginTransaction();

            $borrowing = new Borrowing();
            $borrowing->id = uniqid();
            $borrowing->userId = $request->userId;
            $borrowing->bookId = $request->bookId;
            $borrowing->borrowDate = $request->borrowDate;
            $borrowing->returnDate = $request->returnDate;
            $borrowing->returned = $request->returned;

            $saved = $this->borrowingRepository->create($borrowing);

            Database::commitTransaction();
            return $saved;

        } catch (PDOException $e) {
            Database::rollbackTransaction();
            throw new Exception("Database error while creating borrowing entry.", 0, $e);
        } catch (Throwable $e) {
            Database::rollbackTransaction();
            throw $e;
        }
    }

    /**
     * Fetch one borrowing record by ID.
     * @throws Exception
     */
    public function getBorrowingById(string $id): ?Borrowing
    {
        try {
            return $this->borrowingRepository->findById($id);
        } catch (PDOException $e) {
            throw new Exception("Database error while fetching borrowing #{$id}.", 0, $e);
        }
    }

    /**
     * @throws Throwable
     * @throws Exception
     */
    public function updateBorrowing(BorrowingUpdateRequest $updateRequest): void
    {
        try {
            Database::beginTransaction();

            $borrowing = new Borrowing();
            $borrowing->id = $updateRequest->id;
            $borrowing->borrowDate = $updateRequest->borrowDate;
            $borrowing->returnDate = $updateRequest->returnDate;
            $borrowing->returned = $updateRequest->returned;
            $borrowing->userId = $updateRequest->userId;
            $borrowing->bookId = $updateRequest->bookId;

            $this->borrowingRepository->update($borrowing);

            Database::commitTransaction();
        } catch (PDOException $e) {
            Database::rollbackTransaction();
            throw new Exception("Database error while updating borrowing #{$updateRequest->id}.", 0, $e);
        } catch (Throwable $e) {
            Database::rollbackTransaction();
            throw $e;
        }
    }

    /**
     * Delete a borrowing entry.
     * @throws Exception
     * @throws Throwable
     */
    public function deleteBorrowing(string $id): void
    {
        try {
            Database::beginTransaction();

            $this->borrowingRepository->deleteById($id);

            Database::commitTransaction();
        } catch (PDOException $e) {
            Database::rollbackTransaction();
            throw new Exception("Database error while deleting borrowing #{$id}.", 0, $e);
        } catch (Throwable $e) {
            Database::rollbackTransaction();
            throw $e;
        }
    }

    /**
     * Get all borrowing entries.
     * @throws Exception
     */
    public function getAllBorrowings(): array
    {
        try {
            return $this->borrowingRepository->findAll();
        } catch (PDOException $e) {
            throw new Exception("Database error while fetching all borrowings.", 0, $e);
        }
    }
}