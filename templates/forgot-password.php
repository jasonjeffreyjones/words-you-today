<div class="row justify-content-center">
  <div class="col-md-7 col-lg-6">
    <div class="auth-intro auth-intro-login mb-3">
      <p class="eyebrow mb-2">Password help</p>
      <h2 class="h4 mb-2">Forgot your password?</h2>
      <p class="text-muted mb-0">Enter your email address and we will send a password reset link if an account exists.</p>
    </div>

    <form method="post" action="<?= h(app_url('forgot-password.php')) ?>" class="card card-body shadow-sm auth-card auth-card-login mb-4">
      <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input id="email" name="email" type="email" class="form-control" required value="<?= h($email ?? '') ?>">
      </div>
      <button type="submit" class="btn btn-primary">Email reset link</button>
      <div class="mt-3 small">
        <a href="<?= h(app_url('login.php')) ?>">Back to login</a>
      </div>
    </form>

  </div>
</div>
