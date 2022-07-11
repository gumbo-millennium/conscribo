<?php

declare(strict_types=1);

namespace Gumbo\Conscribo\Objects;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Fluent;

/**
 * An API response.
 * @property-read int $success
 * @property-read array $notifications
 */
class ConscriboApiResponse extends Fluent
{
    public static function make(Response $response): static
    {
        return new self(json_decode($response->body(), true, 32, JSON_THROW_ON_ERROR));
    }

    public function __construct(array $attributes = [])
    {
        // If a result key is present, only use that
        if ($attributes['result']) {
            $attributes = $attributes['result'];
        }

        parent::__construct($attributes);
    }

    public function isSuccesful(): bool
    {
        return object_get($this, 'success') === 1;
    }

    public function getErrorMessage(): ?string
    {
        return object_get($this, 'result.notifications.notification.0');
    }
}
