<?php

namespace Derrickob\GeminiApi;

use Generator;
use GuzzleHttp\Client;

class Gemini
{
    private Client $client;
    private UrlGenerator $urlGenerator;
    private AuthenticationService $authService;
    private string $apiKey;

    private bool $authStatus = false;

    public function __construct(
        Client $client,
        UrlGenerator $urlGenerator,
        AuthenticationService $authService,
        $apiKey
    ) {
        $this->client = $client;
        $this->urlGenerator = $urlGenerator;
        $this->authService = $authService;
        $this->apiKey = "?key={$apiKey}";
        $this->authStatus = $this->authService->getAuthStatus();
    }

    /**
     * Generates content using the specified model and input data.
     *
     * @param string $model The name of the model to use for generation.
     *                      Should start with 'models/' or 'tunedModels/'
     * @param array  $data  The input data for the model, including content and optional parameters.
     *
     * @return string The generated content as a JSON string.
     *
     * @throws \Exception If an invalid model name is provided or authentication is required but not set.
     */
    public function generateContent(string $model, array $data): string
    {
        $this->isValidModel($model);

        if (str_starts_with($model, 'tunedModels/')) {
            $this->isAuthenticated();
            $url = $this->urlGenerator->generateUrl($model, 'generateContent');
            $headers = $this->authService->getHeaders($model);
        } else {
            $url = $this->urlGenerator->generateUrl($model, 'generateContent') . $this->apiKey;
            $headers = $this->authService->getHeaders($model);
        }

        $response = $this->client->post($url, [
            'headers' => $headers,
            'json' => $data,
        ]);

        return $response->getBody()->getContents();
    }

    /**
     * Counts the number of tokens in the given input data for the specified model.
     *
     * @param string $model The name of the model to use for token counting. Should start with 'models/'
     * @param array  $data  The input data for which to count tokens.
     *
     * @return string The token count response as a JSON string.
     *
     * @throws \Exception If an invalid model name is provided.
     */
    public function countTokens(string $model, array $data): string
    {
        $this->isValidModel($model);
        $url = $this->urlGenerator->generateUrl($model, 'countTokens') . $this->apiKey;
        $headers = $this->authService->getHeaders($model);

        $response = $this->client->post($url, [
            'headers' => $headers,
            'json' => $data,
        ]);

        return $response->getBody()->getContents();
    }

    /**
     * Retrieves information about a specific model or tuned model.
     *
     * @param string $modelName The name of the model or tuned model to retrieve.
     *                          Should start with 'models/' or 'tunedModels/'.
     *
     * @return string The model information as a JSON string.
     *
     * @throws \Exception If an invalid model name is provided or authentication is required but not set.
     */
    public function retrieveModel(string $modelName): string
    {
        $this->isValidModel($modelName);
        if (str_starts_with($modelName, 'tunedModels/')) {
            $this->isAuthenticated();
            $url = $this->urlGenerator->buildUrl("/{$modelName}");
        } else {
            $url = $this->urlGenerator->buildUrl("/{$modelName}") . $this->apiKey;
        }

        $headers = $this->authService->getHeaders($modelName);
        $response = $this->client->get($url, [
            'headers' => $headers,
        ]);

        return $response->getBody()->getContents();
    }

    /**
     * Retrieves the metadata for a specific file.
     *
     * @param string $fileName The name of the file to retrieve metadata for.
     *
     * @return string The API response, containing the file's metadata.
     *
     * @throws \Exception If the model name is invalid.
     */
    public function retrieveFile(string $fileName): string
    {
        $this->isValidModel($fileName);
        $url = $this->urlGenerator->buildUrl("/{$fileName}") . $this->apiKey;
        $response = $this->client->get($url);

        return $response->getBody()->getContents();
    }

    /**
     * Deletes a file you uploaded with the Files API.
     *
     * @param string $fileName The fileid of the file to delete.
     *
     * @return string The API response.
     *
     * @throws \Exception If the model name is invalid.
     */
    public function deleteFile(string $fileName): string
    {
        $this->isValidModel($fileName);
        $url = $this->urlGenerator->buildUrl("/{$fileName}") . $this->apiKey;
        $response = $this->client->delete($url);

        return $response->getBody()->getContents();
    }

    /**
     * Lists all available models through the Google Gemini API, including both Gemini and PaLM family models.
     *
     * @return string The API response, containing a list of available models.
     */
    public function listModels(): string
    {
        $url = $this->urlGenerator->listModelsUrl() . $this->apiKey;
        $headers = $this->authService->getHeaders('models');
        $response = $this->client->get($url, [
            'headers' => $headers,
        ]);

        return $response->getBody()->getContents();
    }

    /**
     * Lists tuned models owned by the user.
     *
     * @param null|array $params Optional parameters for pagination and filtering (e.g., page size, page token, filter).
     *
     * @return string The API response, containing a list of tuned models and their metadata.
     *
     * @throws \Exception If the client is not authenticated.
     */
    public function listTunedModels(?array $params = []): string
    {
        $this->isAuthenticated();

        $url = $this->urlGenerator->tunedModelsUrl();
        if (!empty($params)) {
            $queryString = http_build_query($params);
            $url .= '?' . $queryString;
        }
        $headers = $this->authService->getHeaders('tunedModels');
        $response = $this->client->get($url, [
            'headers' => $headers,
        ]);

        return $response->getBody()->getContents();
    }

    /**
     * Retrieves the current state of a tuning job for a specific tuned model.
     *
     * @param string $tunedModel The name of the tuned model to check the tuning state for.
     *
     * @return string The API response, containing information about the tuned model and its tuning status.
     *
     * @throws \Exception If the client is not authenticated or the model name is invalid.
     */
    public function retrieveTuningState(string $tunedModel): string
    {
        $this->isAuthenticated();
        $this->isValidModel($tunedModel);
        $url = $this->urlGenerator->buildUrl("/{$tunedModel}");
        $headers = $this->authService->getHeaders($tunedModel);
        $response = $this->client->get($url, [
            'headers' => $headers,
        ]);

        return $response->getBody()->getContents();
    }

    /**
     * Creates a new tuned model with the provided training data and hyperparameters.
     *
     * @param array $data The request data, including the display name, base model, and tuning task configuration.
     *
     * @return string The API response, containing information about the created tuned model.
     *
     * @throws \Exception If the client is not authenticated.
     */
    public function createTunedModel(array $data): string
    {
        $this->isAuthenticated();
        $url = $this->urlGenerator->tunedModelsUrl();
        $headers = $this->authService->getHeaders('tunedModels');
        $response = $this->client->post($url, [
            'headers' => $headers,
            'json' => $data,
        ]);

        return $response->getBody()->getContents();
    }

    /**
     * Deletes a tuned model.
     *
     * @param string $tunedModel The name of the tuned model to delete.
     *
     * @return string The API response.
     *
     * @throws \Exception If the client is not authenticated or the model name is invalid.
     */
    public function deleteTunedModel(string $tunedModel): string
    {
        $this->isAuthenticated();
        $this->isValidModel($tunedModel);
        $url = $this->urlGenerator->buildUrl("/{$tunedModel}");
        $headers = $this->authService->getHeaders($tunedModel);
        $response = $this->client->delete($url, [
            'headers' => $headers,
        ]);

        return $response->getBody()->getContents();
    }

    /**
     * Uploads a file to the Google Gemini API.
     *
     * @param string     $filePath The path to the local file to upload.
     * @param null|array $metaData Optional metadata for the file, such as display name.
     *
     * @return string The API response, containing information about the uploaded file.
     *
     * @throws \Exception If an error occurs during the upload process.
     */
    public function uploadFile(string $filePath, ?array $meta_data = []): string
    {
        $mimeType = mime_content_type($filePath);
        $fileSize = filesize($filePath);
        $url = $this->urlGenerator->mediaUploadUrl() . $this->apiKey;
        $headers = $this->authService->getHeaders('uploadFile', ['file_size' => $fileSize, 'mime_type' => $mimeType]);

        if (!empty($meta_data)) {
            $response = $this->client->post($url, [
                'headers' => $headers,
                'json' => ['file' => $meta_data],
            ]);
        } else {
            $response = $this->client->post($url, [
                'headers' => $headers,
            ]);
        }

        $uploadUrl = $response->getHeaderLine('X-Goog-Upload-URL');

        $handle = fopen($filePath, 'rb');
        $chunkSize = 8 * 1024 * 1024;
        $offset = 0;

        while (!feof($handle)) {
            $data = fread($handle, $chunkSize);

            print_r($data);
            $end = $offset + strlen($data);
            $command = ($end < $fileSize) ? 'upload' : 'upload, finalize';
            $chunkHeaders = $this->authService->getHeaders('uploadFileChunk', ['length' => strlen($data), 'offset' => $offset, 'command' => $command]);

            $response = $this->client->post($uploadUrl, [
                'headers' => $chunkHeaders,
                'body' => $data,
            ]);

            $offset = $end;
        }

        fclose($handle);

        return $response->getBody()->getContents();
    }

    /**
     * Transfers ownership of a tuned model to another user.
     *
     * @param string $model The name of the tuned model to transfer ownership of.
     * @param array  $data  The request data, including the email address of the new owner.
     *
     * @return string The API response.
     *
     * @throws \Exception If the client is not authenticated or the model name is invalid.
     */
    public function transferOwnership(string $model, array $data): string
    {
        $this->isAuthenticated();
        $this->isValidModel($model);
        $url = $this->urlGenerator->generateUrl($model, 'transferOwnership');
        $headers = $this->authService->getHeaders($model);
        $response = $this->client->post($url, [
            'headers' => $headers,
            'json' => $data,
        ]);

        return $response->getBody()->getContents();
    }

    /**
     * Lists the metadata for files owned by the requesting project.
     *
     * @param null|array $data Optional parameters for pagination and filtering.
     *
     * @return string The API response, containing a list of file metadata.
     */
    public function listFiles(?array $data = []): string
    {
        $url = $this->urlGenerator->filesUrl() . $this->apiKey;
        if (!empty($data)) {
            $queryString = http_build_query($data);
            $url .= '&' . $queryString;
        }
        $response = $this->client->get($url);

        return $response->getBody()->getContents();
    }

    public function embedContents(string $model, array $data): string
    {
        $this->isValidModel($model);
        $url = $this->urlGenerator->generateUrl($model, 'embedContent') . $this->apiKey;
        $headers = $this->authService->getHeaders($model);
        $response = $this->client->post($url, [
            'headers' => $headers,
            'json' => $data,
        ]);

        return $response->getBody()->getContents();
    }

    public function batchEmbedContents(string $model, array $data): string
    {
        $this->isValidModel($model);
        $url = $this->urlGenerator->generateUrl($model, 'batchEmbedContents') . $this->apiKey;
        $headers = $this->authService->getHeaders($model);
        $response = $this->client->post($url, [
            'headers' => $headers,
            'json' => $data,
        ]);

        return $response->getBody()->getContents();
    }

    /**
     * Generates a response from the model given an input, with the ability to stream partial results.
     *
     * @param string   $model     The name of the model to use.
     * @param array    $data      The request data, including the input content.
     * @param null|int $chunkSize The size of the chunks to read from the stream.
     *
     * @return Generator A generator that yields partial results as they become available.
     *
     * @throws \Exception If the model name is invalid.
     */
    public function streamGenerateContent(string $model, array $data, ?int $chunkSize = 1): Generator
    {
        $url = $this->urlGenerator->generateUrl($model, 'streamGenerateContent') . $this->apiKey . "&alt=sse";
        $headers = $this->authService->getHeaders('stream');
        $response = $this->client->post($url, [
            'headers' => $headers,
            'json' => $data,
            'stream' => true,
        ]);

        $body = $response->getBody();
        $buffer = '';

        while (!$body->eof()) {
            $chunk = $body->read($chunkSize);
            $buffer .= $chunk;
            $lines = explode("\n", $buffer);
            $buffer = array_pop($lines);

            foreach ($lines as $line) {
                if (strpos($line, 'data: ') === 0) {
                    $json = substr($line, 6);
                    $data = json_decode($json);
                    if ($data !== null) {
                        yield $data;
                    }
                }
            }
        }

        if (!empty($buffer)) {
            if (strpos($buffer, 'data: ') === 0) {
                $json = substr($buffer, 6);
                $data = json_decode($json);
                if ($data !== null) {
                    yield $data;
                }
            }
        }
    }

    /**
     * Checks if the provided model name is valid.
     *
     * Valid model names should start with either 'models/', 'tunedModels/', or 'files/'.
     *
     * @param string $modelName The model name to validate.
     *
     * @throws \Exception If the model name is invalid.
     */
    private function isValidModel(string $modelName)
    {
        if (!str_starts_with($modelName, 'models/') && !str_starts_with($modelName, 'tunedModels/') && !str_starts_with($modelName, 'files/')) {
            throw new \Exception("Invalid model name. Models must start with either 'models/*' or 'tunedModels/*' and 'files/*' for files");
        }
    }

    /**
     * Checks if the API client is authenticated.
     *
     * @throws \Exception If the client is not authenticated.
     */
    private function isAuthenticated()
    {
        if (!$this->authStatus) {
            throw new \Exception('Service or OAuth Credentials required to use this endpoint');
        }
    }
}
