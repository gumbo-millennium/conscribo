<?php

namespace Gumbo\ConscriboApi\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Gumbo\ConscriboApi\ConscriboApi
 */
class ConscriboApi extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'conscribo-api';
    }
}
