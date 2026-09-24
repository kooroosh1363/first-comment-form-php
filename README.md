# Signal — Secure PHP Comment App

[![Quality](https://github.com/kooroosh1363/first-comment-form-php/actions/workflows/quality.yml/badge.svg)](https://github.com/kooroosh1363/first-comment-form-php/actions/workflows/quality.yml)

Signal modernizes the original 2023 PHP form exercise into a small, production-minded comment application. It demonstrates secure request handling, persistent SQLite storage, accessible server-rendered UI, and automated quality checks without a framework or runtime package dependencies.

## What changed

The original project already demonstrated GET/POST handling and basic PHP validation. The modernized version preserves that learning history while replacing the risky or incomplete parts:

- raw user output → escaped rendering with `htmlspecialchars`
- direct `$_POST` assumptions → normalized defensive input handling
- debug `var_dump` output → deliberate success/error states
- hard-coded result table → real SQLite persistence and recent comments
- fragile URL validation → HTTP/HTTPS normalization and validation
- one mixed PHP file → separated bootstrap, validation, repository, and UI layers
- no request-integrity control → CSRF protection
- no duplicate-submit protection → Post/Redirect/Get
- fixed-width form → responsive, keyboard-friendly UI
- no automated checks → PHP lint + validation/database tests in GitHub Actions

The original implementation remains in Git history, making the repository useful as a visible before/after engineering progression.

## Security model

Signal demonstrates:

- **XSS protection:** all user-controlled content is escaped before HTML rendering.
- **CSRF protection:** POST requests require a cryptographic, session-bound token.
- **SQL injection protection:** database writes use PDO prepared statements.
- **Server-side validation:** browser constraints improve UX, but PHP remains authoritative.
- **URL allowlist:** submitted website links are limited to HTTP and HTTPS.
- **Spam honeypot:** a hidden field blocks basic automated submissions.
- **Post/Redirect/Get:** refreshes do not accidentally repeat the previous POST.
- **Security headers:** CSP, frame denial, no-sniff, referrer, permissions, and no-store headers.
- **Data minimization:** email is stored for the demo but is deliberately excluded from public comment queries.

This is an educational portfolio app, not a full moderation or abuse-prevention platform.

## Architecture

```text
Browser
  │
  ├── GET /
  └── POST /
        │
        ▼
public/index.php
  │
  ├── CSRF + flash state ───── PHP session
  ├── validation ───────────── CommentValidator
  ├── persistence ──────────── CommentRepository
  │                                │
  │                                ▼
  │                           SQLite database
  │
  └── escaped HTML response
```

## Project structure

```text
.
├── public/
│   ├── index.php
│   └── assets/app.css
├── src/
│   ├── bootstrap.php
│   ├── CommentRepository.php
│   ├── CommentValidator.php
│   └── helpers.php
├── storage/
│   └── .gitkeep
├── tests/
│   └── run.php
├── .github/workflows/
│   └── quality.yml
└── comm-form.php              # legacy URL compatibility redirect
```

## Requirements

- PHP 8.1+
- PDO SQLite
- `mbstring` recommended

Check SQLite support:

```bash
php -m | grep -i sqlite
```

## Run locally

From the repository root:

```bash
php -S localhost:8000 -t public
```

Then open:

```text
http://localhost:8000
```

The app creates `storage/comments.sqlite` automatically. Database files are excluded from Git.

To choose another database path:

```bash
COMMENT_DB_PATH=/tmp/signal.sqlite php -S localhost:8000 -t public
```

## Test

Run the test suite:

```bash
php tests/run.php
```

Lint all PHP:

```bash
find public src tests -name '*.php' -print0 | xargs -0 -n1 php -l
```

GitHub Actions runs both on pull requests and pushes to `main`.

## Validation contract

| Field | Rule |
| --- | --- |
| Name | required, 2–80 characters |
| Email | required, valid email, max 254 characters |
| Website | optional, normalized to HTTPS when scheme is omitted, HTTP/HTTPS only, max 255 characters |
| Comment | required, 5–2000 characters |
| Honeypot | must remain empty |

## Accessibility and UX

- semantic labels and landmarks
- per-field errors linked to invalid controls
- visible keyboard focus states
- skip link
- responsive mobile layout
- reduced-motion support
- automatic light/dark color scheme
- explicit privacy note for email
- success/error live-region semantics

## Trade-offs

Signal intentionally stays compact. It does not implement authentication, moderation queues, email verification, pagination, or production-grade rate limiting. Those would change the product scope rather than simply modernize the original learning project.

SQLite is appropriate here because it gives real relational persistence with near-zero operational overhead. A larger hosted product would likely move persistence behind a service layer and use a managed database.

## Deployment

GitHub Pages cannot execute PHP. The repository itself can be public on GitHub, but the application must run on a PHP-capable host or locally with the built-in PHP server.

## Roadmap

Possible next steps only when justified by product requirements:

- moderation state and admin authentication
- versioned database migrations
- stronger rate limiting / anti-abuse controls
- pagination
- PHPUnit / PHPStan if dependency management becomes worthwhile
- deployment recipe for a PHP host

## License

No license is currently included. Add one before redistributing the code as reusable software.
