<?php

declare(strict_types=1);

namespace App\Blog\Query;

use OpenSwoole\Coroutine\PostgreSQL;

final readonly class PostFetcher
{
    public function count(PostgreSQL $connection): int
    {
        $result = $connection->query('SELECT COUNT(p.id) as c FROM blog_posts p');
        $row = $result->fetchRow();

        return $row[0];
    }

    /**
     * @return array[]
     */
    public function all(PostgreSQL $connection, int $offset, int $limit): array
    {
        $stmt = $connection->query(
            <<<SQL
                    SELECT
                        p.slug,
                        p.date,
                        p.content_title AS title,
                        p.content_short AS short
                    FROM
                        blog_posts p
                    ORDER BY p.date DESC
                    LIMIT {$limit} OFFSET {$offset}
                SQL
        );

        return $stmt->fetchAll();
    }
}
