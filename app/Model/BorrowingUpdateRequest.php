<?php

namespace Library\PHP\MVC\Model;

class BorrowingUpdateRequest
{
    public string $borrowDate;  // The borrow date as string (YYYY-MM-DD)
    public ?string $returnDate;  // Nullable, return date as string (YYYY-MM-DD)
    public ?int $returned;
    public ?string $id;
    public ?string $userId;
    public ?string $bookId;
}