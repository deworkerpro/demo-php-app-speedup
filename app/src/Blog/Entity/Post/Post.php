<?php

declare(strict_types=1);

namespace App\Blog\Entity\Post;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'blog_posts')]
#[ORM\Index(columns: ['date'])]
class Post
{
    #[ORM\Column(type: IdType::NAME)]
    #[ORM\Id]
    private Id $id;

    #[ORM\Column]
    private DateTimeImmutable $date;

    #[ORM\Column(unique: true)]
    private string $slug;

    #[ORM\Embedded]
    private Content $content;

    #[ORM\Embedded]
    private Meta $meta;

    public function __construct(Id $id, DateTimeImmutable $date, string $slug, Content $content, Meta $meta)
    {
        $this->id = $id;
        $this->date = $date;
        $this->slug = $slug;
        $this->content = $content;
        $this->meta = $meta;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getContent(): Content
    {
        return $this->content;
    }

    public function getMeta(): Meta
    {
        return $this->meta;
    }
}
