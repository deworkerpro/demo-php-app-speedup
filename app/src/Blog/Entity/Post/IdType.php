<?php

declare(strict_types=1);

namespace App\Blog\Entity\Post;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

final class IdType extends GuidType
{
    public const string NAME = 'blog_post_id';

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): mixed
    {
        return $value instanceof Id ? $value->getValue() : $value;
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): mixed
    {
        return !empty($value) ? new Id((string)$value) : null;
    }
}
