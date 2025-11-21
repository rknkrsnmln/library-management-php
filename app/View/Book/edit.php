<?php

use Library\PHP\MVC\Domain\Book;

/**
 * @var string $title
 * @var Book $book
 * */
?>
<h1><?= $title ?></h1>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form action="/books/update" method="POST">
    <input type="hidden" name="id" value="<?= htmlspecialchars($book->id ?? '') ?>">

    <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input type="text" class="form-control" id="title" name="title"
               value="<?= htmlspecialchars($book->title ?? '') ?>"
               required>
    </div>

    <div class="mb-3">
        <label for="author" class="form-label">Author</label>
        <input type="text" class="form-control" id="author" name="author"
               value="<?= htmlspecialchars($book->author ?? '') ?>"
               required>
    </div>

    <div class="mb-3">
        <label for="publicationYear" class="form-label">Publication Year</label>
        <input type="number" class="form-control" id="publicationYear" name="publicationYear"
               value="<?= $book->publicationYear ?? '' ?>" required>
    </div>

    <div class="mb-3 form-check">
        <!-- Hidden input to ensure a value is sent even when the checkbox is unchecked -->
        <input type="hidden" name="available" value="0">

        <input type="checkbox" class="form-check-input" id="available"
               name="available" value="1" <?= !empty($book->available) ? 'checked' : '' ?>>

        <label class="form-check-label" for="available">Available</label>
    </div>

    <button type="submit" class="btn btn-warning">Update Book</button>
</form>

<a href="/books" class="btn btn-secondary mt-3">Back to Book List</a>

