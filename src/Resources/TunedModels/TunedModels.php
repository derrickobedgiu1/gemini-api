<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Resources\TunedModels;

use Derrickob\GeminiApi\Contracts\Resources\TunedModels\TunedModelsContract;
use Derrickob\GeminiApi\Data\Content;
use Derrickob\GeminiApi\Data\GenerationConfig;
use Derrickob\GeminiApi\Data\Operation;
use Derrickob\GeminiApi\Data\SafetySetting;
use Derrickob\GeminiApi\Data\TextPrompt;
use Derrickob\GeminiApi\Data\Tool;
use Derrickob\GeminiApi\Data\ToolConfig;
use Derrickob\GeminiApi\Data\TunedModel;
use Derrickob\GeminiApi\Requests\TunedModels\CreateTunedModelRequest;
use Derrickob\GeminiApi\Requests\TunedModels\DeleteTunedModelRequest;
use Derrickob\GeminiApi\Requests\TunedModels\GenerateTunedContentRequest;
use Derrickob\GeminiApi\Requests\TunedModels\GenerateTunedTextRequest;
use Derrickob\GeminiApi\Requests\TunedModels\GetTunedModelRequest;
use Derrickob\GeminiApi\Requests\TunedModels\ListTunedModelRequest;
use Derrickob\GeminiApi\Requests\TunedModels\PatchTunedModelRequest;
use Derrickob\GeminiApi\Requests\TunedModels\TransferOwnershipRequest;
use Derrickob\GeminiApi\Responses\GenerateContentResponse;
use Derrickob\GeminiApi\Responses\GenerateTextResponse;
use Derrickob\GeminiApi\Responses\TunedModels\ListTunedModelResponse;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Http\BaseResource;

final class TunedModels extends BaseResource implements TunedModelsContract
{
    /**
     * Creates a tuned model.
     *
     * @link https://ai.google.dev/api/tuning#v1beta.tunedModels.create
     *
     * @param array{
     *     tunedModel: TunedModel,
     *     tunedModelId?: string,
     * } $parameters
     *
     * @return Operation If successful.
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function create(array $parameters): Operation
    {
        /** @var Operation */
        return $this->connector->send(new CreateTunedModelRequest($parameters))->dtoOrFail();
    }

    /**
     * Deletes a tuned model.
     *
     * @link https://ai.google.dev/api/tuning#v1beta.tunedModels.delete
     *
     * @param string $name The resource name of the model.
     *
     * @return bool If successful.
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function delete(string $name): bool
    {
        /** @var bool */
        return $this->connector->send(new DeleteTunedModelRequest($name))->successful();
    }

    /**
     * Generates a response from the model given an input.
     *
     * @link https://ai.google.dev/api/tuning#v1beta.tunedModels.generateContent
     *
     * @param array{
     *      model: string,
     *      contents: string|Content|Content[],
     *      tools?: Tool|Tool[],
     *      toolConfig?: ToolConfig,
     *      safetySettings?: SafetySetting|SafetySetting[],
     *      systemInstruction?: string|Content,
     *      generationConfig?: GenerationConfig,
     *      cachedContent?: string,
     *  } $parameters
     *
     * @return GenerateContentResponse If successful.
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function generateContent(array $parameters): GenerateContentResponse
    {
        /** @var GenerateContentResponse */
        return $this->connector->send(new GenerateTunedContentRequest($parameters))->dtoOrFail();
    }

    /**
     * Generates a response from the model given an input message.
     *
     * @link https://ai.google.dev/api/palm#v1beta.tunedModels.generateText
     *
     * @param array{
     *       model: string,
     *       prompt: TextPrompt,
     *       safetySettings?: SafetySetting|SafetySetting[],
     *       stopSequences?: string[],
     *       temperature?: float,
     *       candidateCount?: int,
     *       maxOutputTokens?: int,
     *       topP?: float,
     *       topK?: int,
     *   } $parameters
     *
     * @return GenerateTextResponse If successful.
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function generateText(array $parameters): GenerateTextResponse
    {
        /** @var GenerateTextResponse */
        return $this->connector->send(new GenerateTunedTextRequest($parameters))->dtoOrFail();
    }

    /**
     * Gets information about a specific TunedModel.
     *
     * @link https://ai.google.dev/api/tuning#v1beta.tunedModels.get
     *
     * @param string $name The resource name of the model.
     *
     * @return TunedModel If successful.
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function get(string $name): TunedModel
    {
        /** @var TunedModel */
        return $this->connector->send(new GetTunedModelRequest($name))->dtoOrFail();
    }

    /**
     * Lists tuned models owned by the user.
     *
     * @link https://ai.google.dev/api/tuning#v1beta.tunedModels.list
     *
     * @param array{
     *     pageSize?: int,
     *     pageToken?: string,
     *     filter?: string,
     * } $parameters
     *
     * @return ListTunedModelResponse If successful.
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function list(array $parameters = []): ListTunedModelResponse
    {
        /** @var ListTunedModelResponse */
        return $this->connector->send(new ListTunedModelRequest($parameters))->dtoOrFail();
    }

    /**
     * Updates a tuned model.
     *
     * @link https://ai.google.dev/api/tuning#v1beta.tunedModels.patch
     *
     * @param array{
     *     name: string,
     *     updateMask: string,
     *     tunedModel: TunedModel,
     * } $parameters
     *
     * @return TunedModel If successful.
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function patch(array $parameters): TunedModel
    {
        /** @var TunedModel */
        return $this->connector->send(new PatchTunedModelRequest($parameters))->dtoOrFail();
    }

    /**
     * Transfers ownership of the tuned model. This is the only way to change ownership of the tuned model.
     * The current owner will be downgraded to writer role.
     *
     * @link https://ai.google.dev/api/tuning/permissions#v1beta.tunedModels.transferOwnership
     *
     * @param array{
     *     name: string,
     *     emailAddress: string,
     * } $parameters
     *
     * @return bool If successful.
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function transferOwnership(array $parameters): bool
    {
        /** @var bool */
        return $this->connector->send(new TransferOwnershipRequest($parameters))->successful();
    }

    public function permissions(): TunedModelPermissions
    {
        return new TunedModelPermissions($this->connector);
    }

    public function operations(): TunedModelOperations
    {
        return new TunedModelOperations($this->connector);
    }
}
