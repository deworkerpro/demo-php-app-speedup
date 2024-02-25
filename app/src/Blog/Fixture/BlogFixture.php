<?php

declare(strict_types=1);

namespace App\Blog\Fixture;

use App\Blog\Entity\Post\Content;
use App\Blog\Entity\Post\Id;
use App\Blog\Entity\Post\Meta;
use App\Blog\Entity\Post\Post;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

final class BlogFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $post = new Post(
            new Id('8821ad9a-1648-4e3e-9db6-5b4eb0425a38'),
            new DateTimeImmutable('-1 month'),
            'lorem-ipsum',
            new Content(
                title: 'Published Post',
                short: '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>',
                text: '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>',
            ),
            new Meta(
                'Post title',
                'Post description'
            )
        );

        $manager->persist($post);

        $manager->flush();
    }
}
