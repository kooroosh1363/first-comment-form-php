<?php

declare(strict_types=1);

final class CommentRepository
{
    public function __construct(private PDO $pdo)
    {
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    public function migrate(): void
    {
        $this->pdo->exec(
            'CREATE TABLE IF NOT EXISTS comments (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                email TEXT NOT NULL,
                website TEXT NULL,
                comment TEXT NOT NULL,
                created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
            )'
        );
    }

    /** @param array{name:string,email:string,website:string,comment:string,company:string} $data */
    public function create(array $data): int
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO comments (name, email, website, comment) VALUES (:name, :email, :website, :comment)'
        );
        $statement->execute([
            ':name' => $data['name'],
            ':email' => $data['email'],
            ':website' => $data['website'] !== '' ? $data['website'] : null,
            ':comment' => $data['comment'],
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    /** @return list<array{id:int,name:string,website:?string,comment:string,created_at:string}> */
    public function latest(int $limit = 20): array
    {
        $limit = max(1, min($limit, 100));
        $statement = $this->pdo->query(
            'SELECT id, name, website, comment, created_at FROM comments ORDER BY id DESC LIMIT ' . $limit
        );

        /** @var list<array{id:int,name:string,website:?string,comment:string,created_at:string}> $rows */
        $rows = $statement->fetchAll();
        return $rows;
    }
}
