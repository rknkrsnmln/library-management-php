<?php

namespace Library\PHP\MVC\Model;

class BookUpdateRequest
{
    public ?string $title;
    public ?string $author;
    public ?int $publicationYear;
    public ?int $available;
    public ?string $id;
}