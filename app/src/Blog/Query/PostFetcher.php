<?php

declare(strict_types=1);

namespace App\Blog\Query;

use Amp\Sql\SqlLink;

final readonly class PostFetcher
{
    public function __construct(
        private SqlLink $connection
    ) {}

    public function count(): int
    {
        $result = $this->connection->query('SELECT COUNT(p.id) as c FROM blog_posts p');
        $row = $result->fetchRow();
        return $row['c'];
    }

    /**
     * @return array[]
     */
    public function all(int $offset, int $limit): array
    {
        $stmt = $this->connection->prepare(
            <<<'SQL'
                    SELECT
                        p.slug,
                        p.date,
                        p.content_title AS title,
                        p.content_short AS short
                    FROM
                        blog_posts p
                    ORDER BY p.date DESC
                    LIMIT :limit OFFSET :offset
                SQL
        );

        $result = $stmt->execute([
            'limit' => $limit,
            'offset' => $offset,
        ]);

        return iterator_to_array($result);
    }
}
