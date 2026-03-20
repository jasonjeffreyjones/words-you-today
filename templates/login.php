<div class="row justify-content-center">
  <div class="col-md-6 col-lg-5">
    <div class="auth-intro auth-intro-login mb-3">
      <p class="eyebrow mb-2">Welcome back</p>
      <h2 class="h4 mb-2">Log in to continue swiping</h2>
      <p class="text-muted mb-0">Use your existing account to get back to today’s signifiers and your stats.</p>
    </div>
    <form method="post" action="<?= h(app_url('login.php')) ?>" class="card card-body shadow-sm auth-card auth-card-login">
      <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input id="email" name="email" type="email" class="form-control" required value="<?= h($email ?? '') ?>">
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input id="password" name="password" type="password" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-primary">Log in</button>
      <div class="d-flex flex-wrap justify-content-between gap-2 mt-3 small">
        <a href="<?= h(app_url('forgot-password.php')) ?>">Forgot your password?</a>
        <a href="<?= h(app_url('signup.php')) ?>">Need an account? Create one</a>
      </div>
    </form>
  </div>
</div>
