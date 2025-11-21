<?php

namespace Library\PHP\MVC\Bootstrap;

use Throwable;

set_exception_handler(function (Throwable $e) {

    // Log the actual error for debugging
    error_log("Uncaught Throwable: " . $e->getMessage());
    error_log($e->getTraceAsString());

    // Send proper HTTP 500 response
    http_response_code(500);

    // Show safe error view
    include __DIR__ . "/../View/Error/500.php";
    exit;
});
