<label for="cars">Choose:</label>

<select name="users" id="cars">
    <?php foreach ($users as $user): ?>
        <option value="<?= $user->id ?>">$user->name</option>
    <?php endforeach; ?>
</select>


<select name="books" id="cars">
    <?php foreach ($books as $book): ?>
        <option value="<?= $book->id ?>">$user->name</option>
    <?php endforeach; ?>
</select>