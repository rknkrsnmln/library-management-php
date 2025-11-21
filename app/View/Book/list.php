<?php

use Library\PHP\MVC\Domain\Book;

/**
 * @var string|null $title
 * @var Book[] $books
 * */
?>
<h1><?= $title ?? 'List of book' ?></h1>

<?php
// Check for the 'error' cookie and retrieve its value
$errorMessage = $_COOKIE['error'] ?? null;

// Optionally, clear the cookie after showing the error message
if ($errorMessage) {
    // Expire the cookie so it will be deleted
    setcookie('error', '', time() - 3600, '/');  // Expired cookie to delete it
}
?>
<!--Display the error message if it exists-->
<?php if (isset($errorMessage) && !empty($errorMessage)): ?>
    <div class="container mt-3">
        <div id="error-message" class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> <?= htmlspecialchars($errorMessage) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
<?php endif; ?>

<a href="/books/add" class="btn btn-success mb-3">Add Book</a>
<?php if (isset($books) && count($books) > 0): ?>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Author</th>
            <th>Year</th>
            <th>Available</th>
            <th>Action</th>
        </tr>
        </thead>

        <tbody>
        <?php foreach ($books as $book): ?>
            <tr>
                <td><?= $book->id ?></td>
                <td><?= $book->title ?></td>
                <td><?= $book->author ?></td>
                <td><?= $book->publicationYear ?></td>
                <td><?= $book->available ? 'Yes' : 'No' ?></td>
                <td>
                    <a href="/books/view/<?= $book->id ?>" class="btn btn-primary btn-sm">View</a>
                    <a href="/books/update/<?= $book->id ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="/books/delete/<?= $book->id ?>"
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('Are you sure?')">
                        Delete
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No books found.</p>
<?php endif; ?>



