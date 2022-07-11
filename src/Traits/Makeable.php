<?php

declare(strict_types=1);

namespace Gumbo\Conscribo\Traits;

trait Makeable
{
    /**
     * Make a new instance of the object.
     *
     * @param array $attributes
     * @return static
     */
    public static function make(array $attributes = [])
    {
        return new static($attributes);
    }
}
