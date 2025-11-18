<?php

namespace Library\PHP\MVC\Model;

class BookRequest
{
    public ?string $title;
    public ?string $author;
    public ?int $publicationYear;
    public ?bool $available;

}