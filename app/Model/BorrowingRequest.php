<?php

namespace Library\PHP\MVC\Model;

class BorrowingRequest
{
    public ?string $userId;
    public ?string $bookId;
    public string $borrowDate;  // The borrow date as string (YYYY-MM-DD)
    public ?string $returnDate;  // Nullable, return date as string (YYYY-MM-DD)
    public int $returned;
}