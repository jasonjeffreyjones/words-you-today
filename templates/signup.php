<div class="row justify-content-center">
  <div class="col-md-6 col-lg-5">
    <div class="auth-intro auth-intro-signup mb-3">
      <p class="eyebrow mb-2">New here?</p>
      <h2 class="h4 mb-2">Create your Words You Today account</h2>
      <p class="text-muted mb-0">Start tracking how you describe yourself day by day. No ads, no sale of personal data.</p>
    </div>
    <form method="post" action="<?= h(app_url('signup.php')) ?>" class="card card-body shadow-sm auth-card auth-card-signup">
      <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input id="email" name="email" type="email" class="form-control" required value="<?= h($email ?? '') ?>">
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input id="password" name="password" type="password" class="form-control" required minlength="8">
      </div>
      <button type="submit" class="btn btn-primary">Sign up</button>
      <div class="mt-3 small">
        Already have an account? <a href="<?= h(app_url('login.php')) ?>">Log in instead</a>
      </div>
    </form>
  </div>
</div>
