<?php

declare(strict_types=1);

namespace Gumbo\Conscribo\Objects;

use Gumbo\Conscribo\Traits\Makeable;
use Illuminate\Support\Fluent;

/**
 * A description of a Conscribo entity, as configured in the config file.
 *
 * @property string $resource
 * @property string[] $fields
 */
class ConscriboEntityDescription extends Fluent
{
    use Makeable;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Validate results
        throw_unless(is_string($this->resource), InvalidArgumentException::class, 'Tried to register a Conscribo object without a resource name');
        throw_unless(is_array($this->fields), InvalidArgumentException::class, 'The fields on {$this->resource} are undefined');
    }
}
