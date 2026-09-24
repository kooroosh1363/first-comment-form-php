<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/src/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = CommentValidator::normalize($_POST);
    $errors = CommentValidator::validate($data);

    if (!csrf_is_valid($_POST['_token'] ?? null)) {
        $errors['form'] = 'Your session expired. Please try again.';
    }

    if ($errors !== []) {
        flash('errors', $errors);
        flash('old', [
            'name' => $data['name'],
            'email' => $data['email'],
            'website' => $data['website'],
            'comment' => $data['comment'],
        ]);
        redirect('/#comment-form');
    }

    $repository->create($data);
    unset($_SESSION['csrf_token']);
    flash('success', 'Thanks — your comment was added successfully.');
    redirect('/#comments');
}

$errors = pull_flash('errors', []);
$old = pull_flash('old', []);
$success = pull_flash('success');
$comments = $repository->latest(20);
$commentCount = count($comments);

function old_value(array $old, string $field): string
{
    return isset($old[$field]) && is_string($old[$field]) ? $old[$field] : '';
}

function field_error(array $errors, string $field): ?string
{
    return isset($errors[$field]) && is_string($errors[$field]) ? $errors[$field] : null;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="A secure, accessible PHP comment form backed by SQLite and server-side validation.">
    <meta name="color-scheme" content="light dark">
    <title>Signal — Secure PHP Comments</title>
    <link rel="stylesheet" href="/assets/app.css">
</head>
<body>
    <a class="skip-link" href="#main-content">Skip to content</a>

    <header class="site-header">
        <a class="brand" href="/" aria-label="Signal comments home">
            <span class="brand-mark" aria-hidden="true">S</span>
            <span>Signal</span>
        </a>
        <span class="header-badge">PHP + SQLite</span>
    </header>

    <main id="main-content" class="page-shell">
        <section class="hero" aria-labelledby="page-title">
            <p class="eyebrow">Small app. Production-minded fundamentals.</p>
            <h1 id="page-title">Leave a useful comment,<br>not a security problem.</h1>
            <p class="hero-copy">A compact PHP comment application demonstrating server-side validation, CSRF protection, safe rendering, prepared SQL statements, and persistent SQLite storage.</p>
            <div class="hero-metrics" aria-label="Application highlights">
                <span><strong><?= $commentCount ?></strong> recent <?= $commentCount === 1 ? 'comment' : 'comments' ?></span>
                <span><strong>0</strong> runtime packages</span>
                <span><strong>100%</strong> server validated</span>
            </div>
        </section>

        <?php if (is_string($success) && $success !== ''): ?>
            <div class="notice notice--success" role="status">
                <span aria-hidden="true">✓</span>
                <p><?= e($success) ?></p>
            </div>
        <?php endif; ?>

        <?php if (($errors['form'] ?? null) !== null): ?>
            <div class="notice notice--error" role="alert">
                <span aria-hidden="true">!</span>
                <p><?= e((string) $errors['form']) ?></p>
            </div>
        <?php endif; ?>

        <div class="content-grid">
            <section class="form-panel" id="comment-form" aria-labelledby="form-title">
                <div class="section-heading">
                    <div>
                        <p class="section-index">01 / Submit</p>
                        <h2 id="form-title">Add a comment</h2>
                    </div>
                    <p>Fields marked * are required.</p>
                </div>

                <form method="post" action="/" novalidate>
                    <input type="hidden" name="_token" value="<?= e(csrf_token()) ?>">

                    <div class="honeypot" aria-hidden="true">
                        <label for="company">Company</label>
                        <input id="company" name="company" type="text" tabindex="-1" autocomplete="off">
                    </div>

                    <div class="field-grid">
                        <div class="field">
                            <label for="name">Name *</label>
                            <input id="name" name="name" type="text" maxlength="80" autocomplete="name" required
                                value="<?= e(old_value($old, 'name')) ?>"
                                <?= field_error($errors, 'name') ? 'aria-invalid="true" aria-describedby="name-error"' : '' ?>>
                            <?php if ($error = field_error($errors, 'name')): ?>
                                <p class="field-error" id="name-error"><?= e($error) ?></p>
                            <?php endif; ?>
                        </div>

                        <div class="field">
                            <label for="email">Email *</label>
                            <input id="email" name="email" type="email" maxlength="254" autocomplete="email" required
                                value="<?= e(old_value($old, 'email')) ?>"
                                <?= field_error($errors, 'email') ? 'aria-invalid="true" aria-describedby="email-error"' : '' ?>>
                            <p class="field-hint">Stored for this demo, never displayed publicly.</p>
                            <?php if ($error = field_error($errors, 'email')): ?>
                                <p class="field-error" id="email-error"><?= e($error) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="field">
                        <label for="website">Website <span class="optional">optional</span></label>
                        <input id="website" name="website" type="text" maxlength="255" inputmode="url" autocomplete="url"
                            placeholder="example.com"
                            value="<?= e(old_value($old, 'website')) ?>"
                            <?= field_error($errors, 'website') ? 'aria-invalid="true" aria-describedby="website-error"' : '' ?>>
                        <?php if ($error = field_error($errors, 'website')): ?>
                            <p class="field-error" id="website-error"><?= e($error) ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="field">
                        <div class="label-row">
                            <label for="comment">Comment *</label>
                            <span>5–2000 characters</span>
                        </div>
                        <textarea id="comment" name="comment" rows="7" minlength="5" maxlength="2000" required
                            <?= field_error($errors, 'comment') ? 'aria-invalid="true" aria-describedby="comment-error"' : '' ?>><?= e(old_value($old, 'comment')) ?></textarea>
                        <?php if ($error = field_error($errors, 'comment')): ?>
                            <p class="field-error" id="comment-error"><?= e($error) ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="form-footer">
                        <p>Protected with CSRF tokens, a spam honeypot, output escaping, and prepared statements.</p>
                        <button class="primary-button" type="submit">Publish comment <span aria-hidden="true">→</span></button>
                    </div>
                </form>
            </section>

            <aside class="principles" aria-labelledby="principles-title">
                <p class="section-index">02 / Principles</p>
                <h2 id="principles-title">What this demo protects</h2>
                <ul>
                    <li><strong>XSS</strong><span>User content is escaped before rendering.</span></li>
                    <li><strong>CSRF</strong><span>Every POST requires a session-bound token.</span></li>
                    <li><strong>SQL injection</strong><span>Database writes use prepared statements.</span></li>
                    <li><strong>Bad input</strong><span>Validation happens again on the server.</span></li>
                    <li><strong>Duplicate POST</strong><span>Post/Redirect/Get avoids accidental resubmission.</span></li>
                </ul>
            </aside>
        </div>

        <section class="comments-section" id="comments" aria-labelledby="comments-title">
            <div class="section-heading comments-heading">
                <div>
                    <p class="section-index">03 / Recent</p>
                    <h2 id="comments-title">Latest comments</h2>
                </div>
                <p>Newest first · up to 20 shown</p>
            </div>

            <?php if ($comments === []): ?>
                <div class="empty-state">
                    <span aria-hidden="true">Ø</span>
                    <div>
                        <h3>No comments yet</h3>
                        <p>Be the first person to exercise the validation pipeline.</p>
                    </div>
                </div>
            <?php else: ?>
                <div class="comment-list">
                    <?php foreach ($comments as $comment): ?>
                        <article class="comment-card">
                            <header>
                                <div class="avatar" aria-hidden="true"><?= e(strtoupper(substr($comment['name'], 0, 1))) ?></div>
                                <div>
                                    <h3><?= e($comment['name']) ?></h3>
                                    <time datetime="<?= e($comment['created_at']) ?>"><?= e($comment['created_at']) ?> UTC</time>
                                </div>
                                <?php if (is_string($comment['website']) && $comment['website'] !== ''): ?>
                                    <a class="website-link" href="<?= e($comment['website']) ?>" target="_blank" rel="noopener noreferrer">Website ↗</a>
                                <?php endif; ?>
                            </header>
                            <p><?= nl2br(e($comment['comment'])) ?></p>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <footer class="site-footer">
        <p>Signal / secure PHP fundamentals</p>
        <p>No framework. No runtime dependencies. No client-side trust.</p>
    </footer>
</body>
</html>
