<?php

declare(strict_types=1);

namespace Gumbo\Conscribo\Exceptions;

class RequestException extends ConscriboException
{
    /**
     * The request was rejected by the API
     */
    public const CODE_REQUEST_FAILED = 1;

    /**
     * The request was accepted by the API, but resulted in an API error
     */
    public const CODE_NOTIFICATION_RECEIVED = 2;
    //
}
