<?php

use Library\PHP\MVC\Domain\Borrowing;

/**
 * @var string|null $title
 * @var Borrowing[] $borrowings
 * */
?>
    <h1><?= $title ?? 'List of all borrowing history' ?></h1>

    <a href="/borrowings/create" class="btn btn-success mb-3">Add Borrowing</a>
<?php if (isset($borrowings) && count($borrowings) > 0): ?>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Borrower Id</th>
            <th>Book Id</th>
            <th>Borrowing Date</th>
            <th>Returning Date</th>
            <th>Returned Status</th>
            <th>Action</th>
        </tr>
        </thead>

        <tbody>
        <?php foreach ($borrowings as $borrowing): ?>
            <tr>
                <td><?= $borrowing->id ?></td>
                <td><?= $borrowing->userId ?></td>
                <td><?= $borrowing->bookId ?></td>
                <td><?= $borrowing->borrowDate ?></td>
                <td><?= $borrowing->returnDate ?></td>
                <td><?= $borrowing->returned ? 'Yes' : 'No' ?></td>
                <td>
                    <a href="/borrowings/update/<?= $borrowing->id ?>" class="btn btn-warning btn-sm">Edit Status</a>
                    <a href="/borrowings/delete/<?= $borrowing->id ?>"
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
    <p>No lending is available.</p>
<?php endif; ?>
<a href="/books" class="btn btn-secondary mt-3">Back to Book List</a>
