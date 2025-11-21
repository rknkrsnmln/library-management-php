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
    <div class="row align-items-center g-lg-5 py-5">
        <div class="col-lg-7 text-center text-lg-start">
            <!--            <h1 class="display-4 fw-bold lh-1 mb-3">Hello -->
            <?php //= $model['user']['name'] ?? '' ?><!--</h1>-->
            <h1 class="display-4 fw-bold lh-1 mb-3">Hello <?= $user['name'] ?? '' ?></h1>
            <p class="col-lg-10 fs-4">by <a target="_blank" href="https://www.github.com/rknkrsnmln">Find My Github</a>
        </div>
        <div class="col-md-10 mx-auto col-lg-5">
            <div class="p-4 p-md-5 border rounded-3 bg-light">
                <div class="form-floating mb-3">
                    <a href="/users/profile" class="w-100 btn btn-lg btn-primary">Profile</a>
                </div>
                <div class="form-floating mb-3">
                    <a href="/users/password" class="w-100 btn btn-lg btn-primary">Password</a>
                </div>
                <div class="form-floating mb-3">
                    <a href="/users/logout" class="w-100 btn btn-lg btn-danger">Logout</a>
                </div>
            </div>
        </div>
    </div>
</div>