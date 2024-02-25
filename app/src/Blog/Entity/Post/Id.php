<?php

declare(strict_types=1);

namespace App\Blog\Entity\Post;

use Ramsey\Uuid\Uuid;
use Stringable;
use Webmozart\Assert\Assert;

final readonly class Id implements Stringable
{
    private string $value;

    public function __construct(string $value)
    {
        Assert::uuid($value);
        $this->value = mb_strtolower($value);
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public static function next(): self
    {
        return new self(Uuid::uuid4()->toString());
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
