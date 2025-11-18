<div class="container col-xl-10 col-xxl-8 px-4 py-5">

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

    <!-- Login Section -->
    <div class="row align-items-center g-lg-5 py-5">
        <div class="col-lg-7 text-center text-lg-start mb-4 mb-lg-0">
            <h1 class="display-4 fw-bold lh-1 mb-3">Login Page</h1>
            <p class="fs-4">
                by <a target="_blank" href="https://www.github.com/rknkrsnmln">Find My GitHub</a>
            </p>
        </div>

        <!-- Login Form -->
        <div class="col-md-10 mx-auto col-lg-5">
            <form class="p-4 p-md-5 border rounded-3 bg-light shadow-sm" method="post" action="/users/login">
                <div class="form-floating mb-3">
                    <input name="id" type="text" class="form-control" id="id" placeholder="id"
                           value="<?= $_POST['id'] ?? '' ?>" required>
                    <label for="id">Id</label>
                </div>
                <div class="form-floating mb-3">
                    <input name="password" type="password" class="form-control" id="password" placeholder="password"
                           required>
                    <label for="password">Password</label>
                </div>
                <div class="d-grid">
                    <button class="btn btn-primary btn-lg" type="submit">Sign On</button>
                </div>
            </form>
        </div>
    </div>
</div>
