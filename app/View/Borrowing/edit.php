<?php

use Library\PHP\MVC\Domain\Borrowing;
use Library\PHP\MVC\Domain\User;
use Library\PHP\MVC\Domain\Book;

/**
 * @var string $title
 * @var Borrowing $borrowing
 * @var User $user
 * @var Book $book
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
<form action="/borrowings/update" method="POST">
    <input type="hidden" name="id" value="<?= htmlspecialchars($borrowing->id ?? '') ?>">
    <input type="hidden" name="bookId" value="<?= htmlspecialchars($borrowing->bookId ?? '') ?>">
    <input type="hidden" name="userId" value="<?= htmlspecialchars($borrowing->userId ?? '') ?>">
    <input type="hidden" name="borrow_date" value="<?= htmlspecialchars($borrowing->borrowDate ?? '') ?>">

    <div class="mb-3">
        <label for="return_date" class="form-label">Returned Date</label>
        <input type="date" class="form-control" id="return_date" name="return_date"
               value="<?= (new DateTime($borrowing->returnDate))->format('Y-m-d') ?>" required>
    </div>

    <div class="mb-3 form-check">
        <!-- Hidden input to ensure a value is sent even when the checkbox is unchecked -->
        <input type="hidden" name="returned" value="0">

        <input type="checkbox" class="form-check-input" id="returned"
               name="returned" value="1" <?= !empty($borrowing->returned) ? 'checked' : '' ?>>

        <label class="form-check-label" for="returned">Returned</label>
    </div>


    <button type="submit" class="btn btn-success">Update Borrowing</button>
</form>


