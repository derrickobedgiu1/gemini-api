<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Tests\Resources\TunedModels;

use Derrickob\GeminiApi\Data\Dataset;
use Derrickob\GeminiApi\Data\Hyperparameters;
use Derrickob\GeminiApi\Data\Operation;
use Derrickob\GeminiApi\Data\TunedModel;
use Derrickob\GeminiApi\Data\TuningExample;
use Derrickob\GeminiApi\Data\TuningExamples;
use Derrickob\GeminiApi\Data\TuningTask;
use Derrickob\GeminiApi\Requests\TunedModels\CreateTunedModelRequest;
use Derrickob\GeminiApi\Requests\TunedModels\DeleteTunedModelRequest;
use Derrickob\GeminiApi\Requests\TunedModels\GenerateTunedContentRequest;
use Derrickob\GeminiApi\Requests\TunedModels\GetTunedModelRequest;
use Derrickob\GeminiApi\Requests\TunedModels\ListTunedModelRequest;
use Derrickob\GeminiApi\Requests\TunedModels\PatchTunedModelRequest;
use Derrickob\GeminiApi\Requests\TunedModels\TransferOwnershipRequest;
use Derrickob\GeminiApi\Responses\GenerateContentResponse;
use Derrickob\GeminiApi\Responses\TunedModels\ListTunedModelResponse;
use Derrickob\GeminiApi\Tests\TestCase;

final class TunedModelsTest extends TestCase
{
    public function testCreate(): void
    {
        $this->mockResponse('tunedModels/create');

        $tuningExamples = new TuningExamples([
            new TuningExample('2', '1'),
            new TuningExample('4', '3'),
            new TuningExample('-2', '-3'),
            new TuningExample('twenty three', 'twenty two'),
            new TuningExample('two hundred one', 'two hundred'),
            new TuningExample('one hundred', 'ninety nine'),
            new TuningExample('9', '8'),
            new TuningExample('-97', '-98'),
            new TuningExample('1001', '1000'),
            new TuningExample('10100001', '10100000'),
            new TuningExample('fourteen', 'thirteen'),
            new TuningExample('eighty one', 'eighty'),
            new TuningExample('two', 'one'),
            new TuningExample('four', 'three'),
            new TuningExample('eight', 'seven'),
        ]);

        $response = $this->gemini->tunedModels()->create([
            'tunedModel' => new TunedModel(
                displayName: 'Saloon Next Number Generator Test',
                tuningTask: new TuningTask(
                    trainingData: new Dataset(
                        examples: $tuningExamples,
                    ),
                    hyperparameters: new Hyperparameters(
                        epochCount: 5,
                        batchSize: 2,
                        learningRate: 0.001,
                    )
                ),
                baseModel: 'models/gemini-1.5-flash-001-tuning'
            ),
        ]);

        $this->mockClient->assertSent(CreateTunedModelRequest::class);
        $this->assertInstanceOf(Operation::class, $response);
    }

    public function testDelete(): void
    {
        $this->mockResponse('tunedModels/delete');

        $response = $this->gemini->tunedModels()->delete('tunedModels/next-number-generator-m1lwcujgc644');

        $this->mockClient->assertSent(DeleteTunedModelRequest::class);
        $this->assertTrue($response);
    }

    public function testGenerateContent(): void
    {
        $this->mockResponse('tunedModels/generateContent');

        $response = $this->gemini->tunedModels()->generateContent([
            'model' => 'tunedModels/next-number-generator-m1lwcujgc644',
            'contents' => '100',
        ]);

        $this->mockClient->assertSent(GenerateTunedContentRequest::class);
        $this->assertInstanceOf(GenerateContentResponse::class, $response);
        $this->assertNotEmpty($response->text());
        $this->assertIsArray($response->candidates[0]->safetyRatings);
        $this->assertNotEmpty($response->candidates[0]->safetyRatings);
        $this->assertIsArray($response->toArray());
    }

    public function testGet(): void
    {
        $this->mockResponse('tunedModels/get');

        $response = $this->gemini->tunedModels()->get('tunedModels/next-number-generator-m1lwcujgc644');

        $this->mockClient->assertSent(GetTunedModelRequest::class);
        $this->assertInstanceOf(TunedModel::class, $response);
        $this->assertIsArray($response->toArray());
    }

    public function testList(): void
    {
        $this->mockResponse('tunedModels/list');

        $response = $this->gemini->tunedModels()->list();

        $this->mockClient->assertSent(ListTunedModelRequest::class);
        $this->assertInstanceOf(ListTunedModelResponse::class, $response);
        $this->assertIsArray($response->toArray());
    }

    public function testPatch(): void
    {
        $this->mockResponse('tunedModels/patch');

        $response = $this->gemini->tunedModels()->patch([
            'name' => 'tunedModels/number-predictor-xc2wgjvvqgyv',
            'updateMask' => 'displayName,description',
            'tunedModel' => new TunedModel(
                displayName: 'Sentence Translator',
                description: 'My next sequence predictor',
            ),
        ]);

        $this->mockClient->assertSent(PatchTunedModelRequest::class);
        $this->assertInstanceOf(TunedModel::class, $response);
        $this->assertIsArray($response->toArray());
    }

    public function testTransferOwnership(): void
    {
        $this->mockResponse('tunedModels/transferOwnership');

        $response = $this->gemini->tunedModels()->transferOwnership([
            'name' => 'tunedModels/saloon-next-number-generator-test-wo3gx4',
            'emailAddress' => 'genai-samples-test-group@googlegroups.com',
        ]);

        $this->mockClient->assertSent(TransferOwnershipRequest::class);
        $this->assertTrue($response);
    }
}
