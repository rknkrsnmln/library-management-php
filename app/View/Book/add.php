<h1>Add New Book</h1>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<form method="POST" action="/books/add">

    <div class="mb-3">
        <label for="title">Title</label>
        <input type="text" name="title" id="title" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="author">Author</label>
        <input type="text" name="author" id="author" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="publicationYear">Year</label>
        <input type="number" name="publicationYear" id="publicationYear" class="form-control" required>
    </div>

    <div class="form-check mb-3">
        <label class="form-check-label">
            <!-- Hidden input to send '0' if unchecked -->
            <input type="hidden" name="available" value="0">
            <!-- Checkbox will send '1' if checked -->
            <input type="checkbox" name="available" class="form-check-input" value="1" checked>
            Available
        </label>
    </div>
    <button class="btn btn-primary">Add Book</button>
</form>
<a href="/books" class="btn btn-secondary mt-3">Back to Book List</a>

