<?php

declare(strict_types=1);

namespace Gumbo\Conscribo\Objects;

use Gumbo\Conscribo\Traits\Makeable;

use Illuminate\Support\Fluent;

/**
 * An API returned Conscribo entity
 */
class ConscriboEntity extends Fluent
{
    use Makeable;
}
