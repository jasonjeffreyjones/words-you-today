<div class="row justify-content-center">
  <div class="col-md-6 col-lg-5">
    <h2 class="h4 mb-3">Create your account</h2>
    <form method="post" action="<?= h(app_url('signup.php')) ?>" class="card card-body shadow-sm">
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
    </form>
  </div>
</div>
