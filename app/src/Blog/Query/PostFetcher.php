<?php

declare(strict_types=1);

namespace App\Blog\Query;

use OpenSwoole\Core\Coroutine\Pool\ClientPool;

final readonly class PostFetcher
{
    public function __construct(
        private ClientPool $connections
    ) {}

    public function count(): int
    {
        $result = $this->connections->get()->query('SELECT COUNT(p.id) as c FROM blog_posts p');
        $row = $result->fetchRow();

        return $row[0];
    }

    /**
     * @return array[]
     */
    public function all(int $offset, int $limit): array
    {
        $stmt = $this->connections->get()->query(
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
