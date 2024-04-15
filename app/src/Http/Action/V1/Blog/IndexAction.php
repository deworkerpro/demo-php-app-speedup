<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Blog;

use Amp\Http\HttpStatus;
use Amp\Http\Server\Request;
use Amp\Http\Server\RequestHandler;
use Amp\Http\Server\Response;
use App\Blog\Query\PostFetcher;
use App\Pagination;
use DateTimeImmutable;

final readonly class IndexAction implements RequestHandler
{
    private const int PER_PAGE = 20;

    public function __construct(
        private PostFetcher $posts
    ) {}

    public function handleRequest(Request $request): Response
    {
        /**
         * @var array{
         *     page?: string,
         *     per_page?: string,
         * } $params
         */
        $params = $request->getQueryParameters();

        $page = (int)($params['page'] ?? '') ?: 1;
        $perPage = min((int)($params['per_page'] ?? '') ?: self::PER_PAGE, self::PER_PAGE);

        $pager = new Pagination($this->posts->count(), $page, $perPage);

        $posts = $this->posts->all($pager->getOffset(), $pager->getLimit());

        return new Response(
            status: HttpStatus::OK,
            headers: ['content-type' => 'application/json'],
            body: json_encode([
                'items' => array_map($this->serialize(...), $posts),
                'pagination' => [
                    'count' => \count($posts),
                    'total' => $pager->getTotalCount(),
                    'per_page' => $pager->getPerPage(),
                    'page' => $pager->getPage(),
                    'pages' => $pager->getPagesCount(),
                ],
            ]),
        );
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
