<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Tests;

use Derrickob\GeminiApi\Gemini;
use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Saloon\Http\Faking\Fixture;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\MockConfig;

abstract class TestCase extends BaseTestCase
{
    protected Gemini $gemini;
    protected MockClient $mockClient;

    protected function setUp(): void
    {
        parent::setUp();
        MockConfig::setFixturePath('tests/Fixtures/GeminiApi');

        $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
        $dotenv->load();

        $credentialsPath = $_ENV['CREDENTIALS_PATH'] ?? getenv('CREDENTIALS_PATH');
        $apiKey = $_ENV['GOOGLE_API_KEY'] ?? getenv('GOOGLE_API_KEY');

        putenv("GOOGLE_APPLICATION_CREDENTIALS={$credentialsPath}");

        $this->mockClient = new MockClient();

        $this->gemini = new Gemini([
            'apiKey' => $apiKey,
        ]);

        $this->gemini->withMockClient($this->mockClient);
    }

    protected function mockResponse(Fixture|string $fixture): void
    {
        if ($fixture instanceof Fixture) {
            $this->mockClient->addResponse(
                $fixture
            );
        } else {
            $this->mockClient->addResponse(
                MockResponse::fixture($fixture)
            );
        }
    }
}
