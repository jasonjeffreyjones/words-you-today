<?php $token = csrf_token(); ?>
<div class="row justify-content-center">
  <div class="col-lg-8">
    <h2 class="h4 mb-3">Words You Today</h2>
    <p class="text-muted">Today: <?= h($appDate) ?></p>

    <?php if ($signifier): ?>
      <div id="wyt-card" class="card shadow-sm text-center p-4 signifier-card" data-signifier-id="<?= (int) $signifier['id'] ?>" data-csrf-token="<?= h($token) ?>">
        <p class="text-uppercase small text-muted mb-3">Does this describe you today?</p>
        <div id="signifier-text" class="display-6 mb-4"><?= h($signifier['text']) ?></div>
        <div class="d-flex justify-content-center gap-3">
          <button type="button" class="btn btn-outline-danger btn-lg px-4" data-answer="0">No</button>
          <button type="button" class="btn btn-success btn-lg px-4" data-answer="1">Yes</button>
        </div>
        <p id="wyt-status" class="small text-muted mt-3 mb-0">Swipe left for no or right for yes.</p>
      </div>
    <?php else: ?>
      <div class="alert alert-success">
        You have answered every available signifier for <?= h($appDate) ?>.
      </div>
    <?php endif; ?>
  </div>
</div>

<?php if ($signifier): ?>
  <script>
    (function () {
      const card = document.getElementById('wyt-card');
      const status = document.getElementById('wyt-status');
      const text = document.getElementById('signifier-text');
      let touchStartX = null;
      let busy = false;

      async function submitAnswer(answer) {
        if (busy) {
          return;
        }

        busy = true;
        status.textContent = 'Saving...';

        const response = await fetch('<?= h(app_url('answer.php')) ?>', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: new URLSearchParams({
            csrf_token: card.dataset.csrfToken,
            signifier_id: card.dataset.signifierId,
            answer: String(answer)
          })
        });

        const payload = await response.json();

        if (!response.ok || !payload.ok) {
          status.textContent = payload.message || 'There was a problem saving your answer.';
          busy = false;
          return;
        }

        if (payload.done) {
          card.innerHTML = '<div class="alert alert-success mb-0">You have answered every available signifier for today.</div>';
          return;
        }

        card.dataset.signifierId = payload.signifier.id;
        text.textContent = payload.signifier.text;
        status.textContent = 'Swipe left for no or right for yes.';
        busy = false;
      }

      card.querySelectorAll('[data-answer]').forEach(function (button) {
        button.addEventListener('click', function () {
          submitAnswer(button.dataset.answer);
        });
      });

      card.addEventListener('touchstart', function (event) {
        touchStartX = event.changedTouches[0].screenX;
      });

      card.addEventListener('touchend', function (event) {
        if (touchStartX === null) {
          return;
        }

        const difference = event.changedTouches[0].screenX - touchStartX;

        if (difference > 60) {
          submitAnswer(1);
        } else if (difference < -60) {
          submitAnswer(0);
        }

        touchStartX = null;
      });
    }());
  </script>
<?php endif; ?>
