<?php

use Library\PHP\MVC\Domain\Book;
use Library\PHP\MVC\Domain\User;

/**
 * @var string $title
 * @var Book[] $books
 * @var User[] $users
 * */
?>
<h1><?= $title ?></h1>

<?php
// Check for the 'error' cookie and retrieve its value
$errorMessage = $_COOKIE['error'] ?? null;

if ($errorMessage) {
    // Expiring the cookies here
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

<form action="/borrowings/create" method="POST">
    <div class="mb-3">
        <label for="book" class="form-label">Choose a Book:</label>
        <select name="book" id="book" class="form-select">
            <?php foreach ($books as $book): ?>
                <option value="<?= htmlspecialchars($book->id) ?>" <?= $book->available ? '' : 'disabled' ?>>
                    <?= htmlspecialchars($book->title) ?> by <?= htmlspecialchars($book->author) ?>
                    (<?= $book->publicationYear ?>)
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="user" class="form-label">Choose a User:</label>
        <select name="user" id="user" class="form-select">
            <?php foreach ($users as $user): ?>
                <?php if ($user->role === 'member'): ?>
                    <option value="<?= htmlspecialchars($user->id) ?>">
                        <?= htmlspecialchars($user->name) ?> (<?= ucfirst($user->role) ?>, <?= ucfirst($user->status) ?>
                        )
                    </option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>
    </div>


    <div class="mb-3">
        <label for="borrow_date" class="form-label">Borrow Date</label>
        <input type="date" class="form-control" id="borrow_date" name="borrow_date"
               value="<?= date('Y-m-d') ?>" required>
    </div>

    <div class="mb-3">
        <label for="return_date" class="form-label">Return Date (Must Be Filled)</label>
        <input type="date" class="form-control" id="return_date" name="return_date"
               value="<?= date('Y-m-d', strtotime('+3 day')) ?>" required>
    </div>

    <div class="form-check mb-3">
        <label class="form-check-label">
            <!-- Hidden input to send '0' if unchecked -->
            <input type="hidden" name="returned" value="0">
            <!-- Checkbox will send '1' if checked -->
            <input type="checkbox" name="returned" class="form-check-input" value="1">
            Returned
        </label>
    </div>

    <button type="submit" class="btn btn-success">Create Borrowing</button>
</form>

<a href="/books" class="btn btn-secondary mt-3">Back to Books List</a>