<h2 class="h4 mb-4">My Stats</h2>
<?php $milestoneProgress = $stats['milestone_progress']; ?>

<section class="mb-4">
  <div class="card card-body shadow-sm milestone-card">
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
      <div>
        <h3 class="h4 mb-2">Milestone Progress</h3>
        <?php if ($milestoneProgress['is_top_tier']): ?>
          <p class="mb-1">You have reached the 4,000+ total responses milestone.</p>
        <?php else: ?>
          <p class="mb-1"><?= h($milestoneProgress['total_label']) ?> responses toward <?= h($milestoneProgress['next_target_label']) ?></p>
        <?php endif; ?>
        <p class="mb-0 text-muted small">
          <?php if ($milestoneProgress['is_top_tier']): ?>
            Your progress bar stays full at the top defined tier.
          <?php else: ?>
            <?= h($milestoneProgress['responses_remaining_label']) ?> to go.
          <?php endif; ?>
        </p>
      </div>
      <div class="milestone-emoji-row" aria-label="Response milestones">
        <?php foreach ($milestoneProgress['milestones'] as $milestone): ?>
          <span
            class="milestone-emoji milestone-emoji-<?= h($milestone['tone']) ?><?= $milestone['earned'] ? ' is-earned' : ' is-muted' ?>"
            title="<?= h($milestone['label']) ?>"
            aria-label="<?= h($milestone['label']) ?>"
          ><?= h($milestone['emoji']) ?></span>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="progress milestone-progress-bar mt-3" role="progressbar" aria-label="Responses toward next milestone" aria-valuenow="<?= h((string) $milestoneProgress['progress_percent']) ?>" aria-valuemin="0" aria-valuemax="100">
      <div class="progress-bar" style="width: <?= h((string) $milestoneProgress['progress_percent']) ?>%;"></div>
    </div>

    <div class="d-flex flex-wrap justify-content-between gap-2 text-muted small mt-2">
      <span><?= h($milestoneProgress['previous_floor_label']) ?></span>
      <span><?= h($milestoneProgress['next_target_label']) ?></span>
    </div>
  </div>
</section>

<div class="row g-3">
  <div class="col-sm-6 col-lg-4">
    <div class="card card-body shadow-sm">
      <div class="text-muted small">Total responses</div>
      <div class="display-6"><?= h(number_format((int) $stats['total_responses'])) ?></div>
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
      <div class="d-flex flex-wrap align-items-center gap-3 mb-2">
        <a href="<?= h(app_url('download-my-data.php')) ?>">Download my data</a>
        <?php if ($dataExport !== null): ?>
          <form method="post" action="<?= h(app_url('stats.php')) ?>" class="m-0">
            <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
            <input type="hidden" name="action" value="update_export">
            <button type="submit" class="btn btn-outline-secondary btn-sm">
              <i class="bi bi-arrow-clockwise" aria-hidden="true"></i>
              Update
            </button>
          </form>
        <?php endif; ?>
      </div>
      <p class="mb-0 text-muted small">
        Generated <?= h((string) ($dataExport['generated_at'] ?? '')) ?> UTC as a CSV of your responses only.
      </p>
    </div>
  <?php else: ?>
    <div class="card card-body shadow-sm">
      <?php if ($dataExport !== null): ?>
        <form method="post" action="<?= h(app_url('stats.php')) ?>">
          <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
          <input type="hidden" name="action" value="update_export">
          <button type="submit" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-clockwise" aria-hidden="true"></i>
            Update
          </button>
        </form>
      <?php else: ?>
        <form method="post" action="<?= h(app_url('stats.php')) ?>">
          <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
          <input type="hidden" name="action" value="prepare_export">
          <button type="submit" class="btn btn-link p-0 align-baseline">Prepare my data download</button>
        </form>
      <?php endif; ?>
      <p class="mb-0 mt-2 text-muted small">
        This creates a CSV file containing the history of your responses.  Download it.  Make into charts, pivot tables, dinner conversations. Go wild!
      </p>
    </div>
  <?php endif; ?>
</section>
