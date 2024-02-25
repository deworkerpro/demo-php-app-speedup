<?php

declare(strict_types=1);

namespace App\Blog\Query;

use Doctrine\DBAL\Connection;

final readonly class PostFetcher
{
    public function __construct(
        private Connection $connection
    ) {}

    public function count(): int
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select('COUNT(p.id)')
            ->from('blog_posts', 'p')
            ->executeQuery();

        return (int)$stmt->fetchOne();
    }

    /**
     * @return array[]
     */
    public function all(int $offset, int $limit): array
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select(
                'p.slug',
                'p.date',
                'p.content_title AS title',
                'p.content_short AS short',
            )
            ->from('blog_posts', 'p')
            ->orderBy('p.date', 'DESC')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->executeQuery();

        return $stmt->fetchAllAssociative();
    }
}
