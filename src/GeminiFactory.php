<?php

namespace Derrickob\GeminiApi;

use GuzzleHttp\Client;

class GeminiFactory
{
    /**
     * Creates and configures a new instance of the Gemini API client.
     *
     * @param string $apiKey The Google Gemini API key.
     * @param array  $config Optional configuration parameters:
     *                       - base_uri: (string) The base URI of the Google Generative Language API.
     *                       - api_version: (string) The API version to use.
     *                       - auth: (array) Authentication configuration:
     *                       - type: (string|null) The authentication type ('service' or 'oauth').
     *                       - projectid: (string|null) The Google Cloud project ID.
     *                       - credentials: (string|array|null) Path to the service account credentials JSON file or OAuth 2.0 credentials array.
     *                       - proxy: (string|array|null) Proxy configuration.
     *
     * @return Gemini A new instance of the Gemini API client.
     */
    public static function create(string $apiKey, array $config = []): Gemini
    {
        $defaultConfig = [
            'base_uri' => 'https://generativelanguage.googleapis.com/',
            'api_version' => 'v1beta',
            'auth' => [
                'type' => null,
                'projectid' => null,
                'credentials' => null,
            ],
            'proxy' => null,
        ];

        $config = array_merge($defaultConfig, $config);

        $httpClientConfig = [
            'base_uri' => $config['base_uri'],
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ];

        if ($config['proxy']) {
            $httpClientConfig['proxy'] = $config['proxy'];
        }

        $httpClient = new Client($httpClientConfig);

        $urlGenerator = new UrlGenerator($config['base_uri'], $config['api_version']);
        $authService = new AuthenticationService($config['auth']);

        return new Gemini($httpClient, $urlGenerator, $authService, $apiKey);
    }
}
