<?php

namespace Derrickob\GeminiApi;

class UrlGenerator
{
    private string $base_uri;
    private string $api_version;
    private $gemini_ai_url;

    public function __construct(string $base_uri, string $api_version)
    {
        $this->base_uri = $base_uri;
        $this->api_version = $api_version;
        $this->gemini_ai_url = $this->base_uri . $this->api_version;
    }

    /**
     * Generates a URL for a specific API endpoint.
     *
     * @param string $model    The model name or path (e.g., 'models/gemini-pro').
     * @param string $endpoint The API endpoint name (e.g., 'generateContent').
     *
     * @return string The generated URL for the specified model and endpoint.
     */
    public function generateUrl(string $model, string $endpoint): string
    {
        return $this->buildUrl("/{$model}:{$endpoint}");
    }

    /**
     * Generates the URL for listing available models.
     *
     * @return string The URL for the /models endpoint.
     */
    public function listModelsUrl(): string
    {
        return $this->buildUrl('/models');
    }

    /**
     * Generates the URL for interacting with tuned models.
     *
     * @return string The URL for the /tunedModels endpoint.
     */
    public function tunedModelsUrl(): string
    {
        return $this->buildUrl('/tunedModels');
    }

    /**
     * Generates the URL for uploading media files.
     *
     * @return string The URL for the media upload endpoint.
     */
    public function mediaUploadUrl(): string
    {
        return $this->base_uri . "upload/{$this->api_version}/files";
    }

    /**
     * Generates the URL for managing files.
     *
     * @return string The URL for the /files endpoint.
     */
    public function filesUrl(): string
    {
        return $this->buildUrl('/files');
    }

    /**
     * Constructs a full URL by combining the base Gemini AI URL with a given path.
     *
     * @param string $path The path to append to the base URL.
     *
     * @return string The full URL.
     */
    public function buildUrl(string $path): string
    {
        return $this->gemini_ai_url . $path;
    }
}
