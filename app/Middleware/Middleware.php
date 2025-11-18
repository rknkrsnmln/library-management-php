<?php

namespace Library\PHP\MVC\Middleware;

interface Middleware
{

    function before(): void;

}