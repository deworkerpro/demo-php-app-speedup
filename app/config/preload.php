<?php

declare(strict_types=1);

$phpFiles = new RegexIterator(
    new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator(__DIR__ . '/../src')
    ),
    '/.+((?<!Test|Fixture)+\.php$)/i',
    RecursiveRegexIterator::GET_MATCH
);

foreach ($phpFiles as $key => $file) {
    opcache_compile_file($file[0]);
}
