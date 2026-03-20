<div class="row justify-content-center">
  <div class="col-md-7 col-lg-6">
    <div class="auth-intro auth-intro-login mb-3">
      <p class="eyebrow mb-2">Password help</p>
      <h2 class="h4 mb-2">Reset your password</h2>
      <p class="text-muted mb-0">Choose a new password for your account.</p>
    </div>

    <?php if ($resetRequest === null): ?>
      <div class="card card-body shadow-sm">
        <p class="mb-3">That reset link is invalid or expired.</p>
        <a class="btn btn-outline-secondary" href="<?= h(app_url('forgot-password.php')) ?>">Prepare a new link</a>
      </div>
    <?php else: ?>
      <form method="post" action="<?= h(app_url('reset-password.php')) ?>" class="card card-body shadow-sm auth-card auth-card-login">
        <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
        <input type="hidden" name="token" value="<?= h($token) ?>">
        <div class="mb-3">
          <label for="password" class="form-label">New password</label>
          <input id="password" name="password" type="password" class="form-control" required minlength="8">
        </div>
        <div class="mb-3">
          <label for="confirm_password" class="form-label">Confirm new password</label>
          <input id="confirm_password" name="confirm_password" type="password" class="form-control" required minlength="8">
        </div>
        <button type="submit" class="btn btn-primary">Reset password</button>
      </form>
    <?php endif; ?>
  </div>
</div>
