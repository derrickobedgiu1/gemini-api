<?php

namespace Derrickob\GeminiApi;

use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Auth\Credentials\UserRefreshCredentials;

class AuthenticationService
{
    private ?string $projectId;
    private ?ServiceAccountCredentials $serviceAccountCredentials;
    private ?UserRefreshCredentials $userRefreshCredentials;

    private bool $authCredentialStatus = false;

    public function __construct(?array $auth = [])
    {
        $this->projectId = $auth['projectid'] ?? null;
        $this->serviceAccountCredentials = null;
        $this->userRefreshCredentials = null;
        $this->authCredentialStatus = false;

        if (isset($auth['type'], $auth['credentials'])) {
            if ($auth['type'] === 'service') {
                $this->setServiceAccountCredentials($auth['credentials']);
            } elseif ($auth['type'] === 'oauth') {
                $this->setOAuthCredentials($auth['credentials']);
            }
        }
    }

    /**
     * Gets the access token.
     *
     * @return string The access token.
     *
     * @throws \Exception If no valid credentials have been set.
     */
    private function getAccessToken(): string
    {
        if ($this->serviceAccountCredentials !== null) {
            $accessToken = $this->serviceAccountCredentials->fetchAuthToken();

            return $accessToken['access_token'];
        } elseif ($this->userRefreshCredentials !== null) {
            return $this->userRefreshCredentials->fetchAuthToken()['access_token'];
        } else {
            throw new \Exception('No valid credentials set.');
        }
    }

    /**
     * Sets the service account credentials.
     *
     * @param string $jsonFile The JSON file containing the service account credentials.
     */
    private function setServiceAccountCredentials(string $jsonFile): void
    {
        $this->serviceAccountCredentials = new ServiceAccountCredentials(
            'https://www.googleapis.com/auth/generative-language.tuning',
            $jsonFile
        );
        $this->authCredentialStatus = true;
    }

    /**
     * Sets the OAuth 2.0 credentials.
     *
     * @param array $config The OAuth 2.0 configuration.
     */
    private function setOAuthCredentials(array $config): void
    {
        $this->userRefreshCredentials = new UserRefreshCredentials(
            'https://www.googleapis.com/auth/generative-language.tuning',
            $config
        );
        $this->authCredentialStatus = true;
    }

    /**
     * Gets the authentication status.
     *
     * @return bool Whether the authentication credentials have been set.
     */
    public function getAuthStatus()
    {
        return $this->authCredentialStatus;
    }

    /**
     * Gets the headers for a given model and parameters.
     *
     * @param string     $model  The model.
     * @param null|array $params The parameters.
     *
     * @return array The headers.
     *
     * @throws \Exception If the authentication credentials have not been set for a tuned model.
     */
    public function getHeaders(string $model, ?array $params = []): array
    {
        $headers = [
            'Content-Type' => 'application/json',
        ];

        if (str_starts_with($model, 'tunedModels')) {
            if (!$this->getAuthStatus()) {
                throw new \Exception('You need to be authenticated to use this endpoint');
            }

            $headers['Authorization'] = 'Bearer ' . $this->getAccessToken();
            $headers['x-goog-user-project'] = $this->projectId;
        }

        if ($model === 'uploadFile') {
            $headers['X-Goog-Upload-Protocol'] = 'resumable';
            $headers['X-Goog-Upload-Command'] = 'start';
            $headers['X-Goog-Upload-Header-Content-Length'] = $params['file_size'];
            $headers['X-Goog-Upload-Header-Content-Type'] = $params['mime_type'];
        }

        if ($model === 'uploadFileChunk') {
            $headers['Content-Length'] = $params['length'];
            $headers['X-Goog-Upload-Offset'] = $params['offset'];
            $headers['X-Goog-Upload-Command'] = $params['command'];
        }

        if ($model === "stream") {
            $headers['Accept'] = 'text/event-stream';
        }

        return $headers;
    }
}
