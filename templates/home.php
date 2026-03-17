<div class="row g-4 align-items-center">
  <div class="col-lg-7">
    <h2 class="display-6 mb-3">A journal without the friction of writing.</h2>
    <p class="lead">Words You Today asks one question repeatedly: does this signifier describe you today?</p>
    <p>You answer with a quick yes or no. Over time, the app helps you understand who you are, who you were, and how unique or typical your patterns might be.</p>
    <div class="d-flex flex-wrap gap-2 mt-4">
      <a class="btn btn-primary btn-lg" href="<?= h($user ? app_url('wyt.php') : app_url('signup.php')) ?>">Start</a>
      <a class="btn btn-outline-secondary btn-lg" href="<?= h(app_url('founding-statement.php')) ?>">Founding Statement</a>
    </div>
  </div>
  <div class="col-lg-5">
    <div class="card shadow-sm">
      <div class="card-body p-4">
        <h3 class="h5">How it works</h3>
        <p class="mb-2">You see one word, phrase or emoji at a time.  Example: <code>introverted</code></p>
        <p class="mb-2">You answer <mark>Does <code>introverted</code> describe me today?</mark></p>
        <p class="mb-2">Swipe left for no. Swipe right for yes.</p>
        <p class="mb-0">Return every day and build a durable record of self-description.</p>
      </div>
    </div>
  </div>
</div>
