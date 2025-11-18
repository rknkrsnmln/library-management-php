<?php
/**
 * @var string|null $title
 * */
?>
<h1><?= $title ?></h1>

<?php if (isset($book)): ?>
    <div>
        <h2><?= htmlspecialchars($book->title) ?></h2>
        <p><strong>Author:</strong> <?= htmlspecialchars($book->author) ?></p>
        <p><strong>Publication Year:</strong> <?= $book->publicationYear ?></p>
        <p><strong>Available:</strong> <?= $book->available ? 'Yes' : 'No' ?></p>
    </div>
    <a href="/books/update/<?= $book->id ?>" class="btn btn-warning btn-sm">Edit</a> |
    <a href="/books/delete/<?= $book->id ?>" onclick="return confirm('Are you sure you want to delete this book?');"
       class="btn btn-danger btn-sm">Delete</a>
<?php else: ?>
    <p>Book not found.</p>
<?php endif; ?>

<a href="/books" class="btn btn-primary btn-sm">Back to Book List</a>


