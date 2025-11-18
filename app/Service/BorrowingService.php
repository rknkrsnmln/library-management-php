<?php

namespace Library\PHP\MVC\Service;

use Exception;
use Library\PHP\MVC\Domain\Borrowing;
use Library\PHP\MVC\Model\BorrowingRequest;
use Library\PHP\MVC\Model\BorrowingUpdateRequest;
use Library\PHP\MVC\Repository\BorrowingRepository;

class BorrowingService
{
    private BorrowingRepository $borrowingRepository;

    public function __construct(BorrowingRepository $borrowingRepository)
    {
        $this->borrowingRepository = $borrowingRepository;
    }

    /**
     * @throws Exception
     */
    public function createBorrowing(BorrowingRequest $request): Borrowing
    {
        // Validate the request (e.g., check that userId and bookId exist)
        if (empty($request->userId) || empty($request->bookId) || empty($request->borrowDate)) {
            throw new Exception("User ID, Book ID, and Borrow Date cannot be empty.");
        }
        $newBorrowing = new Borrowing();
        $newBorrowing->id = uniqid();
        $newBorrowing->userId = $request->userId;
        $newBorrowing->bookId = $request->bookId;
        $newBorrowing->borrowDate = $request->borrowDate;
        $newBorrowing->returnDate = $request->returnDate;
        $newBorrowing->returned = $request->returned;

        return $this->borrowingRepository->create($newBorrowing);
    }

    public function getBorrowingById(string $id): ?Borrowing
    {
        return $this->borrowingRepository->findById($id);
    }

    public function updateBorrowing(BorrowingUpdateRequest $updateRequest): void
    {
        $updatedBorrowing = new Borrowing();
        $updatedBorrowing->returned = $updateRequest->returned;
        $updatedBorrowing->returnDate = $updateRequest->returnDate;
        $updatedBorrowing->borrowDate = $updateRequest->borrowDate;
        $this->borrowingRepository->update($updatedBorrowing);
    }

    public function deleteBorrowing(string $id): void
    {
        $this->borrowingRepository->delete($id);
    }

    public function getAllBorrowings(): array
    {
        return $this->borrowingRepository->findAll();
    }
}