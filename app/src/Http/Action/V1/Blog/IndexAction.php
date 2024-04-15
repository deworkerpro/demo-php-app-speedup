<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Blog;

use App\Blog\Query\PostFetcher;
use App\Pagination;
use DateTimeImmutable;
use OpenSwoole\Http\Request;
use OpenSwoole\Http\Response;

final readonly class IndexAction
{
    private const int PER_PAGE = 20;

    public function __construct(
        private PostFetcher $posts
    ) {}

    public function __invoke(Request $request, Response $response): void
    {
        /**
         * @var array{
         *     page?: string,
         *     per_page?: string,
         * } $params
         */
        $params = $request->get;

        $page = (int)($params['page'] ?? '') ?: 1;
        $perPage = min((int)($params['per_page'] ?? '') ?: self::PER_PAGE, self::PER_PAGE);

        $pager = new Pagination($this->posts->count(), $page, $perPage);

        $posts = $this->posts->all($pager->getOffset(), $pager->getLimit());

        $response->header('content-type', 'application/json');
        $response->end(json_encode([
            'items' => array_map($this->serialize(...), $posts),
            'pagination' => [
                'count' => \count($posts),
                'total' => $pager->getTotalCount(),
                'per_page' => $pager->getPerPage(),
                'page' => $pager->getPage(),
                'pages' => $pager->getPagesCount(),
            ],
        ]));
    }

    private function serialize(array $post): array
    {
        return [
            'slug' => $post['slug'],
            'date' => $post['date']
                ? (new DateTimeImmutable((string)$post['date']))->format(DATE_ATOM)
                : null,
            'title' => $post['title'],
            'short' => $post['short'],
        ];
    }
}
