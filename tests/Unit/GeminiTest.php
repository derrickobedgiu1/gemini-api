<?php

namespace Derrickob\GeminiApi\Tests\Unit;

use Derrickob\GeminiApi\AuthenticationService;
use Derrickob\GeminiApi\Gemini;
use Derrickob\GeminiApi\UrlGenerator;
use Faker\Factory as FakerFactory;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * @group unit
 */

class GeminiTest extends TestCase
{
    private const BASE_URI = 'https://generativelanguage.googleapis.com/';
    private const API_VERSION = 'v1beta';
    private $mockHandler;
    private $httpClient;
    private $urlGenerator;
    private $authService;
    private $gemini;
    private $apiKey;

    private SampleResponseData $sample;

    private $faker;

    private string $access_token;
    private string $projectid;

    protected function setUp(): void
    {
        $this->mockHandler = new MockHandler();
        $handlerStack = HandlerStack::create($this->mockHandler);
        $this->httpClient = new Client(['handler' => $handlerStack]);
        $this->urlGenerator = $this->createMock(UrlGenerator::class);
        $this->authService = $this->createMock(AuthenticationService::class);
        $this->faker = FakerFactory::create();
        $this->apiKey = $this->faker->uuid;
        $this->access_token = $this->faker->uuid;
        $this->projectid = $this->faker->text;
        $this->sample = new SampleResponseData();
    }

    private function buildGemini(bool $status = false)
    {
        if ($status) {
            $this->authService->expects($this->once())
                ->method('getAuthStatus')
                ->willReturn(true);
        } else {
            $this->authService->expects($this->once())
                ->method('getAuthStatus')
                ->willReturn(false);
        }

        $this->gemini = new Gemini($this->httpClient, $this->urlGenerator, $this->authService, $this->apiKey);
    }

    public function testGenerateContent(): void
    {
        $this->buildGemini();
        $model = "models/{$this->faker->word}";
        $data = [
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => $this->faker->text,
                        ],
                    ],
                ],
            ],
        ];

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $this->urlGenerator->expects($this->once())
            ->method('generateUrl')
            ->with($model, 'generateContent')
            ->willReturn(self::BASE_URI . $model . ':generateContent');

        $this->authService->expects($this->once())
            ->method('getHeaders')
            ->with($model)
            ->willReturn($headers);

        $this->mockHandler->append(new Response(200, [], $this->sample->default()));

        $response = $this->gemini->generateContent($model, $data);

        $this->assertEquals($this->sample->default(), $response);
        $this->assertEquals(json_decode($this->sample->default()), json_decode($response));
    }

    public function testGenerateContentWithTunedModel(): void
    {
        $this->buildGemini(true);

        $model = "tunedModels/{$this->faker->word}";
        $data = [
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => $this->faker->text,
                        ],
                    ],
                ],
            ],
        ];

        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->access_token,
            'x-goog-user-project' => $this->projectid,
        ];

        $this->urlGenerator->expects($this->once())
            ->method('generateUrl')
            ->with($model, 'generateContent')
            ->willReturn(self::BASE_URI . $model . ':generateContent');

        $this->authService->expects($this->once())
            ->method('getHeaders')
            ->with($model)
            ->willReturn($headers);

        $this->mockHandler->append(new Response(200, [], $this->sample->default()));

        $response = $this->gemini->generateContent($model, $data);

        $this->assertEquals($this->sample->default(), $response);
        $this->assertEquals(json_decode($this->sample->default()), json_decode($response));
    }

    public function testCountTokens(): void
    {
        $this->buildGemini();
        $model = "models/{$this->faker->word}";
        $data = [
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => $this->faker->text,
                        ],
                    ],
                ],
            ],
        ];

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $this->urlGenerator->expects($this->once())
            ->method('generateUrl')
            ->with($model, 'countTokens')
            ->willReturn(self::BASE_URI . $model . ':countTokens');

        $this->authService->expects($this->once())
            ->method('getHeaders')
            ->with($model)
            ->willReturn($headers);

        $this->mockHandler->append(new Response(200, [], $this->sample->tokens()));

        $response = $this->gemini->countTokens($model, $data);

        $this->assertEquals($this->sample->tokens(), $response);
        $this->assertEquals(json_decode($this->sample->tokens()), json_decode($response));
    }

    public function testRetrieveModel(): void
    {
        $this->buildGemini();
        $model = "models/{$this->faker->word}";
        $headers = [
            'Content-Type' => 'application/json',
        ];

        $this->urlGenerator->expects($this->once())
            ->method('buildUrl')
            ->with("/{$model}")
            ->willReturn(self::BASE_URI . self::API_VERSION . "/{$model}");

        $this->authService->expects($this->once())
            ->method('getHeaders')
            ->with($model)
            ->willReturn($headers);

        $this->mockHandler->append(new Response(200, [], $this->sample->model()));
        $response = $this->gemini->retrieveModel($model);

        $this->assertEquals($this->sample->model(), $response);
        $this->assertEquals(json_decode($this->sample->model()), json_decode($response));
    }

    public function testRetrieveTunedModel(): void
    {
        $this->buildGemini(true);
        $model = "tunedModels/{$this->faker->word}";

        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->access_token,
            'x-goog-user-project' => $this->projectid,
        ];

        $this->urlGenerator->expects($this->once())
            ->method('buildUrl')
            ->with("/{$model}")
            ->willReturn(self::BASE_URI . self::API_VERSION . "/{$model}");

        $this->authService->expects($this->once())
            ->method('getHeaders')
            ->with($model)
            ->willReturn($headers);

        $this->mockHandler->append(new Response(200, [], $this->sample->model()));
        $response = $this->gemini->retrieveModel($model);

        $this->assertEquals($this->sample->model(), $response);
        $this->assertEquals(json_decode($this->sample->model()), json_decode($response));
    }

    public function testRetrieveFile(): void
    {
        $this->buildGemini();
        $fileId = "files/{$this->faker->uuid}";

        $this->urlGenerator->expects($this->once())
            ->method('buildUrl')
            ->with("/{$fileId}")
            ->willReturn(self::BASE_URI . self::API_VERSION . "/{$fileId}");

        $this->mockHandler->append(new Response(200, [], $this->sample->model()));

        $response = $this->gemini->retrieveFile($fileId);

        $this->assertEquals($this->sample->model(), $response);
        $this->assertEquals(json_decode($this->sample->model()), json_decode($response));
    }

    public function testDeleteFile(): void
    {
        $this->buildGemini();
        $fileId = "files/{$this->faker->uuid}";

        $this->urlGenerator->expects($this->once())
            ->method('buildUrl')
            ->with("/{$fileId}")
            ->willReturn(self::BASE_URI . self::API_VERSION . "/{$fileId}");

        $this->mockHandler->append(new Response(200, [], $this->sample->empty()));

        $response = $this->gemini->deleteFile($fileId);

        $this->assertEquals($this->sample->empty(), $response);
        $this->assertEquals(json_decode($this->sample->empty()), json_decode($response));
    }

    public function testListModels(): void
    {
        $this->buildGemini();

        $this->urlGenerator->expects($this->once())
            ->method('listModelsUrl')
            ->willReturn(self::BASE_URI . self::API_VERSION . '/models');

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $this->authService->expects($this->once())
            ->method('getHeaders')
            ->with('models')
            ->willReturn($headers);

        $this->mockHandler->append(new Response(200, [], $this->sample->model()));

        $response = $this->gemini->listModels();

        $this->assertEquals($this->sample->model(), $response);
        $this->assertEquals(json_decode($this->sample->model()), json_decode($response));
    }

    public function testListTunedModels(): void
    {
        $this->buildGemini(true);

        $this->urlGenerator->expects($this->once())
            ->method('tunedModelsUrl')
            ->willReturn(self::BASE_URI . self::API_VERSION . '/tunedModels');

        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->access_token,
            'x-goog-user-project' => $this->projectid,
        ];
        $this->authService->expects($this->once())
            ->method('getHeaders')
            ->with('tunedModels')
            ->willReturn($headers);

        $this->mockHandler->append(new Response(200, [], $this->sample->tuned()));

        $response = $this->gemini->listTunedModels();

        $this->assertEquals($this->sample->tuned(), $response);
        $this->assertEquals(json_decode($this->sample->tuned()), json_decode($response));
    }

    public function testRetrieveTuningState(): void
    {
        $this->buildGemini(true);
        $tunedModelId = "tunedModels/{$this->faker->word}";

        $this->urlGenerator->expects($this->once())
            ->method('buildUrl')
            ->with("/{$tunedModelId}")
            ->willReturn(self::BASE_URI . self::API_VERSION . "/{$tunedModelId}");

        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->access_token,
            'x-goog-user-project' => $this->projectid,
        ];
        $this->authService->expects($this->once())
            ->method('getHeaders')
            ->with($tunedModelId)
            ->willReturn($headers);

        $this->mockHandler->append(new Response(200, [], $this->sample->tuned()));

        $response = $this->gemini->retrieveTuningState($tunedModelId);

        $this->assertEquals($this->sample->tuned(), $response);
        $this->assertEquals(json_decode($this->sample->tuned()), json_decode($response));
    }

    public function testCreateTunedModel(): void
    {
        $this->buildGemini(true);

        $trainingData = [];
        for ($i = 0; $i < 15; $i++) {
            $numberInput = $this->faker->numberBetween(-1000, 10000000);
            $numberOutput = $numberInput + 1;
            $trainingData[] = [
                "text_input" => (string)$numberInput,
                "output" => (string)$numberOutput,
            ];
        }

        $baseModel = "models/{$this->faker->word}";
        $data = [
            "display_name" => $this->faker->text,
            "base_model" => $baseModel,
            "tuning_task" => [
                "hyperparameters" => [
                    "batch_size" => $this->faker->numberBetween(1, 10),
                    "learning_rate" => $this->faker->randomFloat(4, 0.0001, 0.1),
                    "epoch_count" => $this->faker->numberBetween(1, 10),
                ],
                "training_data" => [
                    "examples" => [
                        "examples" => $trainingData,
                    ],
                ],
            ],
        ];

        $this->urlGenerator->expects($this->once())
            ->method('tunedModelsUrl')
            ->willReturn(self::BASE_URI . self::API_VERSION . '/tunedModels');

        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->access_token,
            'x-goog-user-project' => $this->projectid,
        ];
        $this->authService->expects($this->once())
            ->method('getHeaders')
            ->with('tunedModels')
            ->willReturn($headers);

        $this->mockHandler->append(new Response(200, [], $this->sample->tuned()));

        $response = $this->gemini->createTunedModel($data);

        $this->assertEquals($this->sample->tuned(), $response);
        $this->assertEquals(json_decode($this->sample->tuned()), json_decode($response));
    }

    public function testDeleteTunedModel(): void
    {
        $this->buildGemini(true);
        $tunedModelId = "tunedModels/{$this->faker->word}";

        $this->urlGenerator->expects($this->once())
            ->method('buildUrl')
            ->with("/{$tunedModelId}")
            ->willReturn(self::BASE_URI . self::API_VERSION . "/{$tunedModelId}");

        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->access_token,
            'x-goog-user-project' => $this->projectid,
        ];
        $this->authService->expects($this->once())
            ->method('getHeaders')
            ->with($tunedModelId)
            ->willReturn($headers);

        $this->mockHandler->append(new Response(200, [], $this->sample->empty()));

        $response = $this->gemini->deleteTunedModel($tunedModelId);

        $this->assertEquals($this->sample->empty(), $response);
        $this->assertEquals(json_decode($this->sample->empty()), json_decode($response));
    }

    public function testUploadFile(): void
    {
        $this->buildGemini();

        $tempFile = tmpfile();
        fwrite($tempFile, $this->faker->word);
        $tempFilePath = stream_get_meta_data($tempFile)['uri'];

        $metadata = [
            'display_name' => $this->faker->text,
        ];

        $this->urlGenerator->expects($this->once())
            ->method('mediaUploadUrl')
            ->willReturn(self::BASE_URI . "upload/" . self::API_VERSION . "/files");

        $this->authService->expects($this->any())
            ->method('getHeaders')
            ->willReturn(['Content-Type' => 'application/json']);

        $this->mockHandler->append(new Response(200, [], '{"name": "operations/upload-operation-id"}'));
        $this->mockHandler->append(new Response(200, [], $this->sample->file()));

        $response = $this->gemini->uploadFile($tempFilePath, $metadata);

        $this->assertEquals($this->sample->file(), $response);
        $this->assertEquals(json_decode($this->sample->file()), json_decode($response));

        fclose($tempFile);
    }

    public function testTransferOwnership(): void
    {
        $this->buildGemini(true);
        $tunedModelId = "tunedModels/{$this->faker->word}";
        $data = [
            'email_address' => $this->faker->email(),
        ];

        $this->urlGenerator->expects($this->once())
            ->method('generateUrl')
            ->with($tunedModelId, 'transferOwnership')
            ->willReturn(self::BASE_URI . self::API_VERSION . "/{$tunedModelId}:transferOwnership");

        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->access_token,
            'x-goog-user-project' => $this->projectid,
        ];
        $this->authService->expects($this->once())
            ->method('getHeaders')
            ->with($tunedModelId)
            ->willReturn($headers);

        $this->mockHandler->append(new Response(200, [], $this->sample->empty()));

        $response = $this->gemini->transferOwnership($tunedModelId, $data);

        $this->assertEquals($this->sample->empty(), $response);
        $this->assertEquals(json_decode($this->sample->empty()), json_decode($response));
    }

    public function testListFiles(): void
    {
        $this->buildGemini();

        $this->urlGenerator->expects($this->once())
            ->method('filesUrl')
            ->willReturn(self::BASE_URI . self::API_VERSION . '/files');

        $this->mockHandler->append(new Response(200, [], $this->sample->file()));

        $response = $this->gemini->listFiles();

        $this->assertEquals($this->sample->file(), $response);
        $this->assertEquals(json_decode($this->sample->file()), json_decode($response));
    }

    public function testEmbedContents(): void
    {
        $this->buildGemini();
        $model = "models/{$this->faker->word}";
        $data = [
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => $this->faker->text,
                        ],
                    ],
                ],
            ],
        ];

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $this->urlGenerator->expects($this->once())
            ->method('generateUrl')
            ->with($model, 'embedContent')
            ->willReturn(self::BASE_URI . $model . ':embedContent');

        $this->authService->expects($this->once())
            ->method('getHeaders')
            ->with($model)
            ->willReturn($headers);

        $this->mockHandler->append(new Response(200, [], $this->sample->embed()));

        $response = $this->gemini->embedContents($model, $data);

        $this->assertEquals($this->sample->embed(), $response);
        $this->assertEquals(json_decode($this->sample->embed()), json_decode($response));
    }

    public function testBatchEmbedContents(): void
    {
        $this->buildGemini();
        $model = "models/{$this->faker->word}";
        $requests = [
            [
                'model' => $model,
                'content' => [
                    'parts' => [
                        [
                            'text' => $this->faker->sentence,
                        ],
                    ],
                ],
            ],
            [
                'model' => $model,
                'content' => [
                    'parts' => [
                        [
                            'text' => $this->faker->sentence,
                        ],
                    ],
                ],
            ],
            [
                'model' => $model,
                'content' => [
                    'parts' => [
                        [
                            'text' => $this->faker->sentence,
                        ],
                    ],
                ],
            ],
        ];

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $this->urlGenerator->expects($this->once())
            ->method('generateUrl')
            ->with($model, 'batchEmbedContents')
            ->willReturn(self::BASE_URI . $model . ':batchEmbedContents');

        $this->authService->expects($this->once())
            ->method('getHeaders')
            ->with($model)
            ->willReturn($headers);

        $this->mockHandler->append(new Response(200, [], $this->sample->embed()));

        $response = $this->gemini->batchEmbedContents($model, ['requests' => $requests]);

        $this->assertEquals($this->sample->embed(), $response);
        $this->assertEquals(json_decode($this->sample->embed()), json_decode($response));
    }

    public function testStreamGenerateContent(): void
    {
        $this->buildGemini();
        $model = "models/{$this->faker->word}";
        $data = [
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => $this->faker->sentences(10),
                        ],
                    ],
                ],
            ],
        ];

        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'text/event-stream',
        ];

        $this->urlGenerator->expects($this->once())
            ->method('generateUrl')
            ->with($model, 'streamGenerateContent')
            ->willReturn(self::BASE_URI . $model . ':streamGenerateContent');

        $this->authService->expects($this->once())
            ->method('getHeaders')
            ->with('stream')
            ->willReturn($headers);

        $expectedStreamParts = [
            $this->faker->sentence(),
            $this->faker->sentence(),
            $this->faker->sentence(),
            $this->faker->sentence(),
            $this->faker->sentence(),
        ];

        $responseParts = [];
        foreach ($expectedStreamParts as $part) {
            $responseParts[] = "data: {\"content\": \"{$part}\"}\n\n";
        }
        $responseParts[] = "data: [DONE]\n\n";
        $this->mockHandler->append(new Response(200, [], implode('', $responseParts)));

        $collectedStreamData = [];
        $response = $this->gemini->streamGenerateContent($model, $data);
        foreach ($response as $chunk) {
            $collectedStreamData[] = $chunk->content;
        }

        $this->assertEquals($expectedStreamParts, $collectedStreamData);
    }
}
