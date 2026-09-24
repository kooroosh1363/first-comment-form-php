<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/src/CommentValidator.php';
require_once dirname(__DIR__) . '/src/CommentRepository.php';

$tests = [];

function test(string $name, callable $callback): void
{
    global $tests;
    $tests[] = [$name, $callback];
}

function expect_true(bool $condition, string $message = 'Expected condition to be true.'): void
{
    if (!$condition) {
        throw new RuntimeException($message);
    }
}

function expect_same(mixed $expected, mixed $actual, string $message = ''): void
{
    if ($expected !== $actual) {
        throw new RuntimeException($message !== '' ? $message : sprintf(
            'Expected %s, got %s.',
            var_export($expected, true),
            var_export($actual, true)
        ));
    }
}

test('normalizes a bare website to HTTPS', function (): void {
    $data = CommentValidator::normalize([
        'name' => '  Ada Lovelace ',
        'email' => 'ada@example.com ',
        'website' => 'example.com',
        'comment' => ' Useful resource. ',
    ]);

    expect_same('Ada Lovelace', $data['name']);
    expect_same('https://example.com', $data['website']);
    expect_same('Useful resource.', $data['comment']);
});

test('accepts a valid comment payload', function (): void {
    $data = CommentValidator::normalize([
        'name' => 'Grace Hopper',
        'email' => 'grace@example.com',
        'website' => 'https://example.com',
        'comment' => 'Clear and useful demonstration.',
        'company' => '',
    ]);

    expect_same([], CommentValidator::validate($data));
});

test('rejects invalid fields and honeypot input', function (): void {
    $data = CommentValidator::normalize([
        'name' => 'A',
        'email' => 'not-an-email',
        'website' => 'javascript:alert(1)',
        'comment' => 'no',
        'company' => 'spam bot',
    ]);

    $errors = CommentValidator::validate($data);

    foreach (['name', 'email', 'website', 'comment', 'form'] as $field) {
        expect_true(isset($errors[$field]), "Expected validation error for {$field}.");
    }
});

test('rejects oversized values', function (): void {
    $data = CommentValidator::normalize([
        'name' => str_repeat('n', CommentValidator::NAME_MAX + 1),
        'email' => 'valid@example.com',
        'website' => '',
        'comment' => str_repeat('c', CommentValidator::COMMENT_MAX + 1),
        'company' => '',
    ]);

    $errors = CommentValidator::validate($data);
    expect_true(isset($errors['name']));
    expect_true(isset($errors['comment']));
});

test('persists and retrieves comments with SQLite', function (): void {
    expect_true(in_array('sqlite', PDO::getAvailableDrivers(), true), 'pdo_sqlite is required for integration tests.');

    $pdo = new PDO('sqlite::memory:');
    $repository = new CommentRepository($pdo);
    $repository->migrate();

    $repository->create([
        'name' => 'First User',
        'email' => 'first@example.com',
        'website' => '',
        'comment' => 'First comment',
        'company' => '',
    ]);

    $repository->create([
        'name' => 'Second User',
        'email' => 'second@example.com',
        'website' => 'https://example.com',
        'comment' => 'Second comment',
        'company' => '',
    ]);

    $comments = $repository->latest(20);
    expect_same(2, count($comments));
    expect_same('Second User', $comments[0]['name']);
    expect_same('https://example.com', $comments[0]['website']);
    expect_true(!array_key_exists('email', $comments[0]), 'Public reads must not expose email addresses.');
});

$failures = 0;

foreach ($tests as [$name, $callback]) {
    try {
        $callback();
        fwrite(STDOUT, "[pass] {$name}
");
    } catch (Throwable $error) {
        $failures++;
        fwrite(STDERR, "[fail] {$name}: {$error->getMessage()}
");
    }
}

fwrite(STDOUT, sprintf("
%d test(s), %d failure(s).
", count($tests), $failures));
exit($failures === 0 ? 0 : 1);
