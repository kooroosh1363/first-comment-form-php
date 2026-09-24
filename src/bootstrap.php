<?php

declare(strict_types=1);

require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/CommentValidator.php';
require_once __DIR__ . '/CommentRepository.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params([
        'httponly' => true,
        'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'samesite' => 'Lax',
        'path' => '/',
    ]);
    session_start();
}

header("Content-Security-Policy: default-src 'self'; style-src 'self'; img-src 'self' data:; form-action 'self'; base-uri 'none'; frame-ancestors 'none'");
header('Referrer-Policy: no-referrer');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
header('Cache-Control: no-store');

$dbPath = getenv('COMMENT_DB_PATH') ?: dirname(__DIR__) . '/storage/comments.sqlite';
$storageDirectory = dirname($dbPath);
if (!is_dir($storageDirectory) && !mkdir($storageDirectory, 0775, true) && !is_dir($storageDirectory)) {
    throw new RuntimeException('Unable to create the storage directory.');
}

$pdo = new PDO('sqlite:' . $dbPath);
$repository = new CommentRepository($pdo);
$repository->migrate();
