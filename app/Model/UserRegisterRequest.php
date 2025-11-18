<?php

namespace Library\PHP\MVC\Model;

class UserRegisterRequest
{
    public ?string $id = null;
    public ?string $name = null;
    public ?string $password = null;
    public ?string $status = null;
    public ?string $role = null;
}