<?php

declare(strict_types=1);

namespace Gumbo\Conscribo;

use Exception;
use Gumbo\Conscribo\Exceptions\ConfigurationException;
use Gumbo\Conscribo\Exceptions\RequestException;
use Gumbo\Conscribo\Objects\ConscriboApiResponse;
use Illuminate\Cache\Repository as CacheRepository;
use Illuminate\Http\Client\RequestException as HttpClientRequestException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Http;

class ConscriboClient
{
    private const COMMAND_AUTHENTICATE = 'authenticateWithUserAndPass';
    public const COMMAND_LIST_ENTITIES = 'listRelations';
    public const COMMAND_LIST_ENTITY_TYPES = 'listEntityTypes';
    public const COMMAND_LIST_ENTITY_FIELDS = 'listFieldDefinitions';
    public const COMMAND_LIST_ENTITY_GROUPS = 'listEntityGroups';

    private ?string $sessionId;
    private string $userAgent;
    private string $accountName;

    private CacheRepository $cache;

    public function __construct(CacheRepository $cache)
    {
        $this->cache = $cache;
        $this->sessionId = $this->cache->get('conscribo.session_id');

        if (! Config::get('conscribo.account')) {
            throw new ConfigurationException('Account name has not been set');
        }

        $this->accountName = Config::get('conscribo.account');
        $this->userAgent = Config::get('conscribo.user_agent');
    }

    protected function setSessionId(?string $sessionId): void
    {
        $this->cache->set('conscribo.session_id', $sessionId, Date::now()->addMinutes(15));

        $this->sessionId = $sessionId;
    }

    private function authenticate(): void
    {
        try {
            $response = $this->runCommand(self::COMMAND_AUTHENTICATE, [
                'userName' => Config::get('conscribo.auth.username'),
                'passPhrase' => Config::get('conscribo.auth.passphrase'),
            ]);

            $this->setSessionId((string) $response->get('sessionId'));
        } catch (RequestException $exception) {
            if ($exception->getCode() === RequestException::CODE_REQUEST_FAILED) {
                throw $exception;
            }

            throw new ConfigurationException('Invalid credentials');
        }
    }

    /**
     * Run the given command on the API, optionally authenticating if necessary
     * @param string $command
     * @param array $params
     * @return ConscriboApiResponse
     * @throws RequestException
     */
    public function runCommand(string $command, array $params = []): ConscriboApiResponse
    {
        $isAuthenticationRequest = $command === self::COMMAND_AUTHENTICATE;

        // No session yet, try to login
        if (! $this->sessionId && ! $isAuthenticationRequest) {
            $this->authenticate();
        }

        try {
            $response = Http::withUserAgent($this->userAgent)
                ->withHeaders(array_filter([
                    'X-Conscribo-API-Version' => '0.20161212',
                    'X-Conscribo-SessionId' => $this->sessionId,
                ]))
                ->throw()
                ->post("https://secure.conscribo.nl/{$this->accountName}/command.json", json_encode([
                    'request' => [
                        'command' => $command,
                        ...$params,
                    ],
                ]));
        } catch (HttpClientRequestException $exception) {
            throw new RequestException("Server failed to produce a proper response", RequestException::CODE_REQUEST_FAILED, $exception);
        }

        // Convert to Conscribo response
        $response = ConscriboApiResponse::make($response);

        // Check if the session has expired
        if ($response->getErrorMessage() === 'Not authenticated' && ! $isAuthenticationRequest) {
            $this->authenticate();

            return $this->runCommand($command, $params);
        }

        // Throw exception if there is an error
        if (! $response->isSuccesful()) {
            throw new RequestException($response->getErrorMessage(), RequestException::CODE_NOTIFICATION_RECEIVED);
        }

        return $response;
    }
}
