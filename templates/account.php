<div class="row justify-content-center">
  <div class="col-lg-7">
    <h2 class="h4 mb-3">Account</h2>
    <div class="card card-body shadow-sm">
      <p class="mb-2"><strong>Email:</strong> <?= h($user['email']) ?></p>
      <p class="mb-2"><strong>Joined:</strong> <?= h($user['created_at']) ?></p>
      <p class="mb-0 text-muted">Password reset and additional settings can be added after the core WYT flow is stable.</p>
    </div>
  </div>
</div>
