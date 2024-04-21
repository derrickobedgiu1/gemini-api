<?php

namespace Derrickob\GeminiApi\Tests;

use Derrickob\GeminiApi\UrlGenerator;
use PHPUnit\Framework\TestCase;

/**
 * @group unit
 */

class UrlGeneratorTest extends TestCase
{
    private const BASE_URI = 'https://generativelanguage.googleapis.com/';
    private const API_VERSION = 'v1beta';
    private UrlGenerator $urlGenerator;

    public function setUp(): void
    {
        $this->urlGenerator = new urlGenerator(self::BASE_URI, self::API_VERSION);
    }

    public function testGenerateDefaultUrl()
    {
        $model = 'models/gemini-pro';
        $endpoint = 'generateContent';
        $expected = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent';

        $result = $this->urlGenerator->generateUrl($model, $endpoint);

        $this->assertEquals($expected, $result);
    }

    public function testGenerateTunedUrl()
    {
        $model = $model = 'tunedModels/tuned-model-123';
        $endpoint = "generateContent";

        $expected = "https://generativelanguage.googleapis.com/v1beta/tunedModels/tuned-model-123:generateContent";

        $result = $this->urlGenerator->generateUrl($model, $endpoint);

        $this->assertEquals($expected, $result);
    }

    public function testListModelsUrl()
    {
        $expected = 'https://generativelanguage.googleapis.com/v1beta/models';

        $result = $this->urlGenerator->listModelsUrl();

        $this->assertEquals($expected, $result);
    }

    public function testTunedModelsUrl()
    {
        $expected = 'https://generativelanguage.googleapis.com/v1beta/tunedModels';

        $result = $this->urlGenerator->tunedModelsUrl();

        $this->assertEquals($expected, $result);
    }

    public function testMediaUploadUrl()
    {
        $expected = 'https://generativelanguage.googleapis.com/upload/v1beta/files';

        $result = $this->urlGenerator->mediaUploadUrl();

        $this->assertEquals($expected, $result);
    }

    public function testFilesUrl()
    {
        $expected = 'https://generativelanguage.googleapis.com/v1beta/files';

        $result = $this->urlGenerator->filesUrl();

        $this->assertEquals($expected, $result);
    }

    public function testBuildUrl()
    {
        $path = '/test/path';
        $expected = 'https://generativelanguage.googleapis.com/v1beta' . $path;

        $result = $this->urlGenerator->buildUrl($path);

        $this->assertEquals($expected, $result);
    }
}
