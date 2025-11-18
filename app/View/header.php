<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <?php
    /**
     * @var array{title: string|null} $model
     * @var array{user: array{name: string|null}} $model
     * */
    ?>
    <title><?= $model['title'] ?? 'Library Management System' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
            crossorigin="anonymous"></script>
</head>
<?php if (!empty($model['user']['name'])): ?>
    <header class="bg-dark text-white p-3">
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="/">LMS</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link  <?= ($_SERVER['REQUEST_URI'] === "/") ? 'active fw-bold' : ''; ?>"
                               aria-current="page" href="/">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link  <?= ($_SERVER['REQUEST_URI'] === "/books") ? 'active fw-bold' : ''; ?>"
                               aria-current="page" href="/books">Book Collections</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link  <?= ($_SERVER['REQUEST_URI'] === "/about") ? 'active fw-bold' : ''; ?>"
                               aria-current="page" href="/about">About</a>
                        </li>
                    </ul>
                    <?php if (str_starts_with(rtrim($_SERVER['REQUEST_URI'], '/'), "/books")): ?>
                        <form class="d-flex" role="search">
                            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search"/>
                            <button class="btn btn-outline-success" type="submit">Search</button>
                        </form>
                    <?php endif; ?>

                </div>
            </div>
        </nav>
    </header>
<?php endif; ?>
<body>