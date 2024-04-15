<?php

declare(strict_types=1);

namespace App\Blog\Query;

use Exception;
use React\Mysql\MysqlClient;
use React\Mysql\MysqlResult;
use React\Promise\Deferred;
use React\Promise\PromiseInterface;

final readonly class PostFetcher
{
    public function __construct(
        private MysqlClient $connection
    ) {}

    /**
     * @return PromiseInterface<int>
     */
    public function count(): PromiseInterface
    {
        $deferred = new Deferred();

        $this->connection->query('SELECT COUNT(p.id) as c FROM blog_posts p')->then(
            static function (MysqlResult $result) use ($deferred): void {
                $deferred->resolve((int)($result->resultRows[0]['c'] ?? ''));
            },
            static function (Exception $e) use ($deferred): void {
                $deferred->reject($e);
            }
        );

        /**
         * @var PromiseInterface<int>
         */
        return $deferred->promise();
    }

    /**
     * @return PromiseInterface<array[]>
     */
    public function all(int $offset, int $limit): PromiseInterface
    {
        $deferred = new Deferred();

        $this->connection->query(
            <<<'SQL'
                    SELECT
                        p.slug,
                        p.date,
                        p.content_title AS title,
                        p.content_short AS short
                    FROM
                        blog_posts p
                    ORDER BY p.date DESC
                    LIMIT ? OFFSET ?
                SQL,
            [$limit, $offset]
        )->then(
            static function (MysqlResult $result) use ($deferred): void {
                $deferred->resolve($result->resultRows);
            },
            static function (Exception $e) use ($deferred): void {
                $deferred->reject($e);
            }
        );

        /**
         * @var PromiseInterface<array[]>
         */
        return $deferred->promise();
    }
}
