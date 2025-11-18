<?php

namespace Library\PHP\MVC\Domain;

class Borrowing
{
    public ?string $id;
    public ?string $userId;
    public ?string $bookId;
    public ?string $borrowDate;  //  (YYYY-MM-DD)
    public ?string $returnDate;  //  (YYYY-MM-DD)
    public int $returned;
}