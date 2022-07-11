<?php

declare(strict_types=1);

if (! function_exists('test_path')) {
    function test_path(string $path = ''): string
    {
        return __DIR__ . '/' . $path;
    }
}
