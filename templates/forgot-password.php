<div class="row justify-content-center">
  <div class="col-md-7 col-lg-6">
    <div class="auth-intro auth-intro-login mb-3">
      <p class="eyebrow mb-2">Password help</p>
      <h2 class="h4 mb-2">Forgot your password?</h2>
      <p class="text-muted mb-0">Enter your email address and we will prepare a password reset link for your account.</p>
    </div>

    <form method="post" action="<?= h(app_url('forgot-password.php')) ?>" class="card card-body shadow-sm auth-card auth-card-login mb-4">
      <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input id="email" name="email" type="email" class="form-control" required value="<?= h($email ?? '') ?>">
      </div>
      <button type="submit" class="btn btn-primary">Prepare reset link</button>
      <div class="mt-3 small">
        <a href="<?= h(app_url('login.php')) ?>">Back to login</a>
      </div>
    </form>

    <?php if (!empty($resetLink)): ?>
      <div class="card card-body shadow-sm border-success-subtle">
        <h3 class="h5 mb-2">Reset link ready</h3>
        <p class="text-muted mb-2">Email delivery is not wired up yet, so the link is shown here for now.</p>
        <p class="mb-0"><a href="<?= h($resetLink) ?>"><?= h($resetLink) ?></a></p>
      </div>
    <?php endif; ?>
  </div>
</div>
