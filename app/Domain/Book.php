<?php

namespace Library\PHP\MVC\Domain;

class Book
{
    public string $id;
    public string $title;
    public string $author;
    public int $publicationYear;
    public int $available;

    // Constructor to initialize the object with values
    public function __construct()
    {
    }

    // Additional method to toggle the availability
    public function toggleAvailability(): void
    {
        $this->available = !$this->available;
    }
}

