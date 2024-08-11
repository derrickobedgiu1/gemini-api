<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Requests;

use Derrickob\GeminiApi\Contracts\AuthenticateContract;
use Derrickob\GeminiApi\Enums\AuthMethod;
use Google\Auth\ApplicationDefaultCredentials;
use Google\Auth\CredentialsLoader;
use Google\Auth\FetchAuthTokenCache;
use Google\Auth\Middleware\AuthTokenMiddleware;
use GuzzleHttp\HandlerStack;
use Psr\Cache\CacheItemPoolInterface;
use RuntimeException;
use Saloon\Contracts\RequestMiddleware;
use Saloon\Http\PendingRequest;

final class AuthenticateRequest implements RequestMiddleware, AuthenticateContract
{
    /**
     * @param string                      $apiKey        Google Gemini API Key.
     * @param array<string, mixed>|null   $credentials   Manual credentials to use for authentication.
     * @param string[]|null               $scopes        Scopes to use for service account.
     * @param CacheItemPoolInterface|null $cacheItemPool PSR-6 interface for caching service account tokens.
     * @param array<string, mixed>|null   $cacheConfig   Cache configuration for service account authentication.
     * @param array<string, mixed>|null   $proxy         Proxy configuration for requests.
     */
    public function __construct(
        private readonly string                  $apiKey,
        private readonly ?array                  $credentials = null,
        private readonly ?array                  $scopes = null,
        private readonly ?CacheItemPoolInterface $cacheItemPool = null,
        private readonly ?array                  $cacheConfig = null,
        private readonly ?array                  $proxy = null,
    ) {
        //
    }

    public function __invoke(PendingRequest $pendingRequest): void
    {
        $request = $pendingRequest->getRequest();
        if (!$request instanceof Request) {
            throw new RuntimeException('Request must extend the base Request class');
        }

        match ($request->auth) {
            AuthMethod::API_KEY => $this->setupApiKey($pendingRequest),
            AuthMethod::ADC => $this->setupADC($pendingRequest),
        };
    }

    public function setupApiKey(PendingRequest $pendingRequest): void
    {
        $pendingRequest->headers()->add('x-goog-api-key', $this->apiKey);

        if ($this->proxy !== null) {
            $config = ['proxy' => $this->proxy];
            $pendingRequest->config()->merge($config);
        }
    }

    public function setupADC(PendingRequest $pendingRequest): void
    {
        $scopes = $this->scopes ?? $this->defaultScopes();

        if ($this->credentials !== null) {
            $credentials = CredentialsLoader::makeCredentials($scopes, $this->credentials);

            if ($this->cacheItemPool instanceof CacheItemPoolInterface) {
                $credentials = new FetchAuthTokenCache(fetcher: $credentials, cacheConfig: $this->cacheConfig, cache: $this->cacheItemPool);
            }

            $middleware = new AuthTokenMiddleware($credentials);
        } else {
            $middleware = ApplicationDefaultCredentials::getMiddleware(scope: $scopes, cacheConfig: $this->cacheConfig, cache: $this->cacheItemPool);
        }

        $handlerStack = HandlerStack::create();
        $handlerStack->push($middleware);

        $config = [
            'handler' => $handlerStack,
            'auth' => 'google_auth',
        ];

        if ($this->proxy !== null) {
            $config['proxy'] = $this->proxy;
        }

        $pendingRequest->config()->merge($config);
    }

    public static function create(
        string                  $apiKey,
        ?array                  $credentials = null,
        ?array                  $scopes = null,
        ?CacheItemPoolInterface $cacheItemPool = null,
        ?array                  $cacheConfig = null,
        ?array                  $proxy = null
    ): self {
        return new self(
            apiKey: $apiKey,
            credentials: $credentials,
            scopes: $scopes,
            cacheItemPool: $cacheItemPool,
            cacheConfig: $cacheConfig,
            proxy: $proxy,
        );
    }

    public function defaultScopes(): array
    {
        return [
            'https://www.googleapis.com/auth/generative-language.tuning',
            'https://www.googleapis.com/auth/generative-language.retriever',
        ];
    }
}
