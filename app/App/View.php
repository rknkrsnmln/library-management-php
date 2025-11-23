<?php

namespace Library\PHP\MVC\App;

use JetBrains\PhpStorm\NoReturn;

class View
{

    public static function render(string $view, $model): void
    {
        require __DIR__ . '/../View/header.php';
        require __DIR__ . '/../View/' . $view . '.php';
        require __DIR__ . '/../View/footer.php';
    }

    public static function renderNew(string $view, array $model = []): void
    {
        $viewFile = __DIR__ . '/../View/' . $view . '.php';
        $layout = __DIR__ . '/../View/base.php';


        // Extract variables untuk diberikan ke view
        extract($model);

        // Variable $content yang akan dimasukan didalam view template
        $content = $viewFile;

        require $layout;
    }

    public static function redirect(string $url): void
    {
        header("Location: $url");
        if (getenv("mode") != "test") {
            exit();
        }
    }

    public static function redirectError(string $url, string $errorMessage = ''): void
    {
        // Nge-set cookie 'error' jika ada error dan berikan pesan errornya
        session_start();
        if ($errorMessage) {
            setcookie('error', $errorMessage, time() + 3600, '/');  // 1 hour expiration
        }

        // Perform the redirect
        header("Location: $url");
        if (getenv("mode") != "test") {
            exit();
        }
    }

}

