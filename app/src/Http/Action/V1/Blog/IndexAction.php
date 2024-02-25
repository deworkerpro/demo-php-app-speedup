<?php

declare(strict_types=1);

namespace App\Http\Action\V1\Blog;

use App\Blog\Query\PostFetcher;
use App\Http\Response\JsonResponse;
use App\Pagination;
use DateTimeImmutable;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final readonly class IndexAction implements RequestHandlerInterface
{
    private const int PER_PAGE = 20;

    public function __construct(
        private PostFetcher $posts
    ) {}

    public function handle(ServerRequestInterface $request): ResponseInterface
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

        $pager = new Pagination($this->posts->count(), $page, $perPage);

        $posts = $this->posts->all($pager->getOffset(), $pager->getLimit());

        return new JsonResponse([
            'items' => array_map($this->serialize(...), $posts),
            'pagination' => [
                'count' => \count($posts),
                'total' => $pager->getTotalCount(),
                'per_page' => $pager->getPerPage(),
                'page' => $pager->getPage(),
                'pages' => $pager->getPagesCount(),
            ],
        ]);
    }

    public function serialize(array $post): array
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
