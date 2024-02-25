<?php

declare(strict_types=1);

namespace App\Blog\Test\Entity\Post;

use App\Blog\Entity\Post\Content;
use App\Blog\Entity\Post\Id;
use App\Blog\Entity\Post\Meta;
use App\Blog\Entity\Post\Post;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class CreateTest extends TestCase
{
    public function testSuccess(): void
    {
        $post = new Post(
            $id = Id::next(),
            $date = new DateTimeImmutable(),
            $slug = 'test-slug',
            $content = new Content(
                title: '',
                short: '',
                text: ''
            ),
            $meta = new Meta(
                'Meta Title',
                'Meta Description'
            )
        );

        self::assertEquals($id, $post->getId());
        self::assertEquals($date, $post->getDate());
        self::assertSame($slug, $post->getSlug());
        self::assertEquals($content, $post->getContent());
        self::assertEquals($meta, $post->getMeta());
    }
}
