<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

start_session_if_needed();
$flash = get_flash();
$user = current_user();
$pageTitle = $pageTitle ?? APP_NAME;
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="A low-friction way to record who you are today.">
    <meta name="author" content="Jason Jeffrey Jones">
    <title><?= h($pageTitle) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="<?= h(app_url('public-assets/style.css')) ?>" rel="stylesheet">
  </head>
  <body>
    <div class="container py-4">
      <header class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4 border-bottom pb-3">
        <div>
          <h1 class="h3 mb-1"><a class="text-decoration-none text-dark" href="<?= h(app_url()) ?>"><?= h(APP_NAME) ?></a></h1>
          <p class="text-muted mb-0">Track your daily self one signifier at a time.</p>
        </div>
        <nav class="d-flex flex-wrap gap-2">
          <a class="btn btn-outline-secondary btn-sm" href="<?= h(app_url()) ?>">Home</a>
          <?php if ($user): ?>
            <a class="btn btn-outline-secondary btn-sm" href="<?= h(app_url('wyt.php')) ?>">Start</a>
            <a class="btn btn-outline-secondary btn-sm" href="<?= h(app_url('stats.php')) ?>">My Stats</a>
            <a class="btn btn-outline-secondary btn-sm" href="<?= h(app_url('account.php')) ?>">Account</a>
            <a class="btn btn-outline-secondary btn-sm" href="<?= h(app_url('logout.php')) ?>">Logout</a>
          <?php else: ?>
            <a class="btn btn-outline-secondary btn-sm" href="<?= h(app_url('login.php')) ?>">Login</a>
            <a class="btn btn-primary btn-sm" href="<?= h(app_url('signup.php')) ?>">Sign Up</a>
          <?php endif; ?>
        </nav>
      </header>

      <?php if ($flash): ?>
        <div class="alert alert-<?= h($flash['type']) ?>" role="alert">
          <?= h($flash['message']) ?>
        </div>
      <?php endif; ?>
