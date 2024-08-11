<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Tests\Resources;

use Derrickob\GeminiApi\Data\Message;
use Derrickob\GeminiApi\Data\MessagePrompt;
use Derrickob\GeminiApi\Data\Model;
use Derrickob\GeminiApi\Data\TextPrompt;
use Derrickob\GeminiApi\Requests\Models\BatchEmbedContentsRequest;
use Derrickob\GeminiApi\Requests\Models\BatchEmbedTextRequest;
use Derrickob\GeminiApi\Requests\Models\CountMessageTokensRequest;
use Derrickob\GeminiApi\Requests\Models\CountTextTokensRequest;
use Derrickob\GeminiApi\Requests\Models\CountTokensRequest;
use Derrickob\GeminiApi\Requests\Models\EmbedContentRequest;
use Derrickob\GeminiApi\Requests\Models\EmbedTextRequest;
use Derrickob\GeminiApi\Requests\Models\GenerateContentRequest;
use Derrickob\GeminiApi\Requests\Models\GenerateMessageRequest;
use Derrickob\GeminiApi\Requests\Models\GenerateTextRequest;
use Derrickob\GeminiApi\Requests\Models\GetModelRequest;
use Derrickob\GeminiApi\Requests\Models\ListModelsRequest;
use Derrickob\GeminiApi\Responses\GenerateContentResponse;
use Derrickob\GeminiApi\Responses\GenerateTextResponse;
use Derrickob\GeminiApi\Responses\Models\BatchEmbedContentsResponse;
use Derrickob\GeminiApi\Responses\Models\BatchEmbedTextResponse;
use Derrickob\GeminiApi\Responses\Models\CountMessageTokensResponse;
use Derrickob\GeminiApi\Responses\Models\CountTextTokensResponse;
use Derrickob\GeminiApi\Responses\Models\CountTokensResponse;
use Derrickob\GeminiApi\Responses\Models\EmbedContentResponse;
use Derrickob\GeminiApi\Responses\Models\EmbedTextResponse;
use Derrickob\GeminiApi\Responses\Models\GenerateMessageResponse;
use Derrickob\GeminiApi\Responses\Models\ListModelsResponse;
use Derrickob\GeminiApi\Tests\TestCase;

final class ModelsTest extends TestCase
{
    public function testBatchEmbedContents(): void
    {
        $this->mockResponse('models/batchEmbedContents');

        $response = $this->gemini->models()->batchEmbedContents([
            'model' => 'models/text-embedding-004',
            'requests' => [
                'What is the meaning of life?',
                'How much wood would a woodchuck chuck?',
                'How does the brain work?',
            ],
        ]);

        $this->mockClient->assertSent(BatchEmbedContentsRequest::class);
        $this->assertInstanceOf(BatchEmbedContentsResponse::class, $response);
        $this->assertNotEmpty($response->embeddings);
        $this->assertIsArray($response->toArray());
    }

    public function testBatchEmbedText(): void
    {
        $this->mockResponse('models/batchEmbedText');

        $response = $this->gemini->models()->batchEmbedText([
            'model' => 'models/embedding-gecko-001',
            'texts' => [
                'What is the meaning of life?',
                'How much wood would a woodchuck chuck?',
                'How does the brain work?',
            ],
        ]);

        $this->mockClient->assertSent(BatchEmbedTextRequest::class);
        $this->assertInstanceOf(BatchEmbedTextResponse::class, $response);
        $this->assertNotEmpty($response->embeddings);
        $this->assertIsArray($response->toArray());
    }

    public function testCountMessageTokens(): void
    {
        $this->mockResponse('models/countMessageTokens');

        $response = $this->gemini->models()->countMessageTokens([
            'model' => 'models/chat-bison-001',
            'prompt' => new MessagePrompt(
                messages: [
                    new Message(
                        content: 'Hello World!',
                    )],
            ),
        ]);

        $this->mockClient->assertSent(CountMessageTokensRequest::class);
        $this->assertInstanceOf(CountMessageTokensResponse::class, $response);
        $this->assertNotEmpty($response->tokenCount);
        $this->assertIsArray($response->toArray());
    }

    public function testCountTextTokens(): void
    {
        $this->mockResponse('models/countTextTokens');

        $response = $this->gemini->models()->countTextTokens([
            'model' => 'models/text-bison-001',
            'prompt' => new TextPrompt('Hello World!'),
        ]);

        $this->mockClient->assertSent(CountTextTokensRequest::class);
        $this->assertInstanceOf(CountTextTokensResponse::class, $response);
        $this->assertNotEmpty($response->tokenCount);
        $this->assertIsArray($response->toArray());
    }

    public function testCountTokens(): void
    {
        $this->mockResponse('models/countTokens');

        $response = $this->gemini->models()->countTokens([
            'model' => 'models/gemini-1.5-flash',
            'contents' => 'Hello World!',
        ]);

        $this->mockClient->assertSent(CountTokensRequest::class);
        $this->assertInstanceOf(CountTokensResponse::class, $response);
        $this->assertNotEmpty($response->totalTokens);
        $this->assertIsArray($response->toArray());
    }

    public function testEmbedContent(): void
    {
        $this->mockResponse('models/embedContent');

        $response = $this->gemini->models()->embedContent([
            'model' => 'models/text-embedding-004',
            'content' => 'Hello World',
        ]);

        $this->mockClient->assertSent(EmbedContentRequest::class);
        $this->assertInstanceOf(EmbedContentResponse::class, $response);
        $this->assertNotEmpty($response->embedding->values);
        $this->assertIsArray($response->toArray());
    }

    public function testEmbedText(): void
    {
        $this->mockResponse('models/embedText');

        $response = $this->gemini->models()->embedText([
            'model' => 'models/embedding-gecko-001',
            'text' => 'Hello World!',
        ]);

        $this->mockClient->assertSent(EmbedTextRequest::class);
        $this->assertInstanceOf(EmbedTextResponse::class, $response);
        $this->assertNotEmpty($response->embedding->value);
        $this->assertIsArray($response->toArray());
    }

    public function testGenerateContent(): void
    {
        $this->mockResponse('models/generateContent');

        $response = $this->gemini->models()->generateContent([
            'model' => 'models/gemini-1.5-flash',
            'systemInstruction' => 'You are a helpful assistant',
            'contents' => 'Hello, world!',
        ]);

        $this->mockClient->assertSent(GenerateContentRequest::class);
        $this->assertInstanceOf(GenerateContentResponse::class, $response);
        $this->assertNotEmpty($response->text());
        $this->assertIsArray($response->candidates[0]->safetyRatings);
        $this->assertIsArray($response->toArray());
    }

    public function testGenerateMessage(): void
    {
        $this->mockResponse('models/generateMessage');

        $response = $this->gemini->models()->generateMessage([
            'model' => 'models/chat-bison-001',
            'prompt' => new MessagePrompt(
                messages: [
                    new Message(
                        content: 'What is the meaning of life?',
                    )],
            ),
            'temperature' => 0.1,
        ]);

        $this->mockClient->assertSent(GenerateMessageRequest::class);
        $this->assertInstanceOf(GenerateMessageResponse::class, $response);
        $this->assertIsArray($response->toArray());
    }

    public function testGenerateText(): void
    {
        $this->mockResponse('models/generateText');

        $response = $this->gemini->models()->generateText([
            'model' => 'models/text-bison-001',
            'prompt' => new TextPrompt('What is the meaning of life?'),
        ]);

        $this->mockClient->assertSent(GenerateTextRequest::class);
        $this->assertInstanceOf(GenerateTextResponse::class, $response);
        $this->assertIsArray($response->toArray());
    }

    public function testGet(): void
    {
        $this->mockResponse('models/get');

        $modelName = 'models/gemini-1.5-flash';
        $response = $this->gemini->models()->get($modelName);

        $this->mockClient->assertSent(GetModelRequest::class);
        $this->assertInstanceOf(Model::class, $response);
        $this->assertEquals($modelName, $response->name);
        $this->assertIsArray($response->toArray());
    }

    public function testList(): void
    {
        $this->mockResponse('models/list');

        $response = $this->gemini->models()->list();

        $this->mockClient->assertSent(ListModelsRequest::class);
        $this->assertInstanceOf(ListModelsResponse::class, $response);
        $this->assertNotEmpty($response->models);
        $this->assertIsArray($response->toArray());
    }
}
