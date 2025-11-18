<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Library System' ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="/">Library Management System</a>

        <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link" href="/books">Books</a></li>
            <li class="nav-item"><a class="nav-link" href="/users/register">Register</a></li>
            <li class="nav-item"><a class="nav-link" href="/users/login">Login</a></li>
            <li class="nav-item"><a class="nav-link" href="/users/logout">Logout</a></li>
        </ul>
    </div>
</nav>
<?php
/**
 * @var string|null $content
 **/
?>
<?php if (isset($content)): ?>
    <div class="container mt-4">
        <?php require $content; ?>
    </div>
<?php endif; ?>


<footer class="text-center mt-5 mb-3 text-muted">
    &copy; <?= date('Y') ?> Library System
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
