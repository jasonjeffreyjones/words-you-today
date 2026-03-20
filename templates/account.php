<div class="row justify-content-center">
  <div class="col-lg-7">
    <h2 class="h4 mb-3">Account</h2>
    <div class="card card-body shadow-sm mb-4">
      <p class="mb-2"><strong>Email:</strong> <?= h($user['email']) ?></p>
      <p class="mb-0"><strong>Joined:</strong> <?= h($user['created_at']) ?></p>
    </div>

    <div class="card card-body shadow-sm mb-4">
      <h3 class="h5 mb-3">Change email</h3>
      <form method="post" action="<?= h(app_url('account.php')) ?>">
        <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
        <input type="hidden" name="action" value="change_email">
        <div class="mb-3">
          <label for="email" class="form-label">New email address</label>
          <input id="email" name="email" type="email" class="form-control" required value="<?= h($user['email']) ?>">
        </div>
        <button type="submit" class="btn btn-primary">Update email</button>
      </form>
    </div>

    <div class="card card-body shadow-sm">
      <h3 class="h5 mb-3">Change password</h3>
      <form method="post" action="<?= h(app_url('account.php')) ?>">
        <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
        <input type="hidden" name="action" value="change_password">
        <div class="mb-3">
          <label for="current_password" class="form-label">Current password</label>
          <input id="current_password" name="current_password" type="password" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="new_password" class="form-label">New password</label>
          <input id="new_password" name="new_password" type="password" class="form-control" required minlength="8">
        </div>
        <div class="mb-3">
          <label for="confirm_password" class="form-label">Confirm new password</label>
          <input id="confirm_password" name="confirm_password" type="password" class="form-control" required minlength="8">
        </div>
        <button type="submit" class="btn btn-primary">Update password</button>
      </form>
    </div>
  </div>
</div>
