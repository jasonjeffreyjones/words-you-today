<h2 class="h4 mb-4">My Stats</h2>
<div class="row g-3">
  <div class="col-sm-6 col-lg-4">
    <div class="card card-body shadow-sm">
      <div class="text-muted small">Total responses</div>
      <div class="display-6"><?= (int) $stats['total_responses'] ?></div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-4">
    <div class="card card-body shadow-sm">
      <div class="text-muted small">Yes percentage</div>
      <div class="display-6"><?= h((string) $stats['yes_percentage']) ?>%</div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-4">
    <div class="card card-body shadow-sm">
      <div class="text-muted small">Responses today</div>
      <div class="display-6"><?= (int) $stats['responses_today'] ?></div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-4">
    <div class="card card-body shadow-sm">
      <div class="text-muted small">Yes count</div>
      <div class="display-6"><?= (int) $stats['yes_count'] ?></div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-4">
    <div class="card card-body shadow-sm">
      <div class="text-muted small">No count</div>
      <div class="display-6"><?= (int) $stats['no_count'] ?></div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-4">
    <div class="card card-body shadow-sm">
      <div class="text-muted small">Active days</div>
      <div class="display-6"><?= (int) $stats['active_days'] ?></div>
    </div>
  </div>
</div>

<section class="mt-5">
  <h3 class="h4 mb-3">My Data</h3>

  <?php if (user_export_is_downloadable($dataExport ?? null)): ?>
    <div class="card card-body shadow-sm">
      <p class="mb-2">
        <a href="<?= h(app_url('download-my-data.php')) ?>">Download my data</a>
      </p>
      <p class="mb-0 text-muted small">
        Generated <?= h((string) ($dataExport['generated_at'] ?? '')) ?> UTC as a CSV of your responses only.
      </p>
    </div>
  <?php else: ?>
    <div class="card card-body shadow-sm">
      <form method="post" action="<?= h(app_url('stats.php')) ?>">
        <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
        <input type="hidden" name="action" value="prepare_export">
        <button type="submit" class="btn btn-link p-0 align-baseline">Prepare my data download</button>
      </form>
      <p class="mb-0 mt-2 text-muted small">
        This creates a CSV file containing only your responses and stores it outside the public web directory.
      </p>
    </div>
  <?php endif; ?>
</section>
