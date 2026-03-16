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
