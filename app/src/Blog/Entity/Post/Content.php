<?php

declare(strict_types=1);

namespace App\Blog\Entity\Post;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final readonly class Content
{
    #[ORM\Column]
    private string $title;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $short;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $text;

    public function __construct(string $title, ?string $short, ?string $text)
    {
        $this->title = $title;
        $this->short = $short;
        $this->text = $text;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getShort(): ?string
    {
        return $this->short;
    }

    public function getText(): ?string
    {
        return $this->text;
    }
}
