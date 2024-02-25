<?php

declare(strict_types=1);

namespace Test\Functional\V1\Blog;

use App\Blog\Entity\Post\Content;
use App\Blog\Entity\Post\Id;
use App\Blog\Entity\Post\Meta;
use App\Blog\Entity\Post\Post;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

final class IndexFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $post = new Post(
            new Id('8821ad9a-1648-4e3e-9db6-5b4eb0425a38'),
            new DateTimeImmutable('2023-12-26T08:53:33+00:00'),
            'lorem-ipsum',
            new Content(
                title: 'Lorem ipsum',
                short: '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>',
                text: '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod.</p>',
            ),
            new Meta(
                'Lorem title',
                'Lorem description'
            )
        );

        $manager->persist($post);

        $post = new Post(
            new Id('e6198925-fd98-435d-b179-b99922cb7c1f'),
            new DateTimeImmutable('2024-01-26T08:53:33+00:00'),
            'dolor sit amet',
            new Content(
                title: 'Dolor sit amet',
                short: '<p>Dolor sit amet, consectetur adipiscing elit</p>',
                text: '<p>Dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt.</p>',
            ),
            new Meta(
                'Dolor title',
                'Dolor description'
            )
        );

        $manager->persist($post);

        $manager->flush();
    }
}
