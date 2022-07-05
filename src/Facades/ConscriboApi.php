<?php

namespace Gumbo\Conscribo\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Gumbo\Conscribo\ConscriboApi
 */
class ConscriboApi extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'conscribo-api';
    }
}
