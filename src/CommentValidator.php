<?php

declare(strict_types=1);

final class CommentValidator
{
    public const NAME_MIN = 2;
    public const NAME_MAX = 80;
    public const EMAIL_MAX = 254;
    public const WEBSITE_MAX = 255;
    public const COMMENT_MIN = 5;
    public const COMMENT_MAX = 2000;

    /**
     * @param array<string, mixed> $input
     * @return array{name:string,email:string,website:string,comment:string,company:string}
     */
    public static function normalize(array $input): array
    {
        $website = trim((string) ($input['website'] ?? ''));
        if ($website !== '' && !preg_match('~^https?://~i', $website)) {
            $website = 'https://' . $website;
        }

        return [
            'name' => trim((string) ($input['name'] ?? '')),
            'email' => trim((string) ($input['email'] ?? '')),
            'website' => $website,
            'comment' => trim((string) ($input['comment'] ?? '')),
            'company' => trim((string) ($input['company'] ?? '')),
        ];
    }

    /**
     * @param array{name:string,email:string,website:string,comment:string,company:string} $data
     * @return array<string, string>
     */
    public static function validate(array $data): array
    {
        $errors = [];

        $nameLength = self::length($data['name']);
        if ($data['name'] === '') {
            $errors['name'] = 'Please enter your name.';
        } elseif ($nameLength < self::NAME_MIN || $nameLength > self::NAME_MAX) {
            $errors['name'] = sprintf('Name must be between %d and %d characters.', self::NAME_MIN, self::NAME_MAX);
        }

        if ($data['email'] === '') {
            $errors['email'] = 'Please enter your email address.';
        } elseif (self::length($data['email']) > self::EMAIL_MAX || filter_var($data['email'], FILTER_VALIDATE_EMAIL) === false) {
            $errors['email'] = 'Please enter a valid email address.';
        }

        if ($data['website'] !== '') {
            $validWebsite = self::length($data['website']) <= self::WEBSITE_MAX
                && filter_var($data['website'], FILTER_VALIDATE_URL) !== false
                && in_array(strtolower((string) parse_url($data['website'], PHP_URL_SCHEME)), ['http', 'https'], true);

            if (!$validWebsite) {
                $errors['website'] = 'Website must be a valid HTTP or HTTPS URL.';
            }
        }

        $commentLength = self::length($data['comment']);
        if ($data['comment'] === '') {
            $errors['comment'] = 'Please enter a comment.';
        } elseif ($commentLength < self::COMMENT_MIN || $commentLength > self::COMMENT_MAX) {
            $errors['comment'] = sprintf('Comment must be between %d and %d characters.', self::COMMENT_MIN, self::COMMENT_MAX);
        }

        if ($data['company'] !== '') {
            $errors['form'] = 'Unable to submit this form.';
        }

        return $errors;
    }

    private static function length(string $value): int
    {
        return function_exists('mb_strlen') ? mb_strlen($value) : strlen($value);
    }
}
