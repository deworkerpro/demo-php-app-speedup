<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Blog;

use App\Blog\Query\PostFetcher;
use App\Pagination;
use DateTimeImmutable;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;
use React\Promise\PromiseInterface;

final readonly class IndexAction
{
    private const int PER_PAGE = 20;

    public function __construct(
        private PostFetcher $posts
    ) {}

    public function __invoke(ServerRequestInterface $request): PromiseInterface|ResponseInterface
    {
        /**
         * @var array{
         *     page?: string,
         *     per_page?: string,
         * } $params
         */
        $params = $request->getQueryParams();

        $page = (int)($params['page'] ?? '') ?: 1;
        $perPage = min((int)($params['per_page'] ?? '') ?: self::PER_PAGE, self::PER_PAGE);

        return $this->posts->count()
            ->then(function (int $count) use ($page, $perPage) {
                $pager = new Pagination($count, $page, $perPage);

                return $this->posts->all($pager->getOffset(), $pager->getLimit())
                    ->then(function (array $posts) use ($pager) {
                        return Response::json([
                            'items' => array_map($this->serialize(...), $posts),
                            'pagination' => [
                                'count' => \count($posts),
                                'total' => $pager->getTotalCount(),
                                'per_page' => $pager->getPerPage(),
                                'page' => $pager->getPage(),
                                'pages' => $pager->getPagesCount(),
                            ],
                        ]);
                });
            });
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
