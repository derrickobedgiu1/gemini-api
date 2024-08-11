<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Resources;

use Derrickob\GeminiApi\Contracts\Resources\ModelContract;
use Derrickob\GeminiApi\Data\Content;
use Derrickob\GeminiApi\Data\GenerationConfig;
use Derrickob\GeminiApi\Data\GroundingPassages;
use Derrickob\GeminiApi\Data\MessagePrompt;
use Derrickob\GeminiApi\Data\Model;
use Derrickob\GeminiApi\Data\SafetySetting;
use Derrickob\GeminiApi\Data\SemanticRetrieverConfig;
use Derrickob\GeminiApi\Data\TextPrompt;
use Derrickob\GeminiApi\Data\Tool;
use Derrickob\GeminiApi\Data\ToolConfig;
use Derrickob\GeminiApi\Enums\AnswerStyle;
use Derrickob\GeminiApi\Enums\TaskType;
use Derrickob\GeminiApi\Requests\Models\BatchEmbedContentsRequest;
use Derrickob\GeminiApi\Requests\Models\BatchEmbedTextRequest;
use Derrickob\GeminiApi\Requests\Models\CountMessageTokensRequest;
use Derrickob\GeminiApi\Requests\Models\CountTextTokensRequest;
use Derrickob\GeminiApi\Requests\Models\CountTokensRequest;
use Derrickob\GeminiApi\Requests\Models\EmbedContentRequest;
use Derrickob\GeminiApi\Requests\Models\EmbedTextRequest;
use Derrickob\GeminiApi\Requests\Models\GenerateAnswerRequest;
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
use Derrickob\GeminiApi\Responses\Models\GenerateAnswerResponse;
use Derrickob\GeminiApi\Responses\Models\GenerateMessageResponse;
use Derrickob\GeminiApi\Responses\Models\ListModelsResponse;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Http\BaseResource;

final class Models extends BaseResource implements ModelContract
{
    /**
     * Generates multiple embeddings from the model given input text in a synchronous call.
     *
     * @link https://ai.google.dev/api/embeddings#v1beta.models.batchEmbedContents
     *
     * @param array{
     *      model: string,
     *      requests: (string[]|array{
     *         array{
     *             content: Content|string,
     *             taskType?: TaskType,
     *             title?: string,
     *             outputDimensionality?: int,
     *         }
     *     }),
     *  } $parameters
     *
     * @return BatchEmbedContentsResponse If successful.
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function batchEmbedContents(array $parameters): BatchEmbedContentsResponse
    {
        /**
         * @var BatchEmbedContentsResponse
         */
        return $this->connector->send(new BatchEmbedContentsRequest($parameters))->dtoOrFail();
    }

    /**
     * Generates multiple embeddings from the model given input text in a synchronous call.
     *
     * @link https://ai.google.dev/api/palm#v1beta.models.batchEmbedText
     *
     * @param array{
     *      model: string,
     *      texts?: string[],
     *      requests?: array{
     *          text: string,
     *      },
     *  } $parameters
     *
     * @return BatchEmbedTextResponse If successful.
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function batchEmbedText(array $parameters): BatchEmbedTextResponse
    {
        /**
         * @var BatchEmbedTextResponse
         */
        return $this->connector->send(new BatchEmbedTextRequest($parameters))->dtoOrFail();
    }

    /**
     * Runs a model's tokenizer on a string and returns the token count.
     *
     * @link https://ai.google.dev/api/palm#v1beta.models.countMessageTokens
     *
     * @param array{
     *      model: string,
     *      prompt: MessagePrompt,
     * } $parameters
     *
     * @return CountMessageTokensResponse If successful.
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function countMessageTokens(array $parameters): CountMessageTokensResponse
    {
        /** @var CountMessageTokensResponse */
        return $this->connector->send(new CountMessageTokensRequest($parameters))->dtoOrFail();
    }

    /**
     * Runs a model's tokenizer on a text and returns the token count.
     *
     * @link https://ai.google.dev/api/palm#v1beta.models.countTextTokens
     *
     * @param array{
     *      model: string,
     *      prompt: TextPrompt,
     * } $parameters
     *
     * @return CountTextTokensResponse If successful.
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function countTextTokens(array $parameters): CountTextTokensResponse
    {
        /** @var CountTextTokensResponse */
        return $this->connector->send(new CountTextTokensRequest($parameters))->dtoOrFail();
    }

    /**
     * Runs a model's tokenizer on input content and returns the token count.
     *
     * @link https://ai.google.dev/api/tokens#v1beta.models.countTokens
     *
     * @param array{
     *      model: string,
     *      contents?: string|Content|Content[],
     *      generateContentRequest?: array{
     *          model: string,
     *          contents: string|Content|Content[],
     *          systemInstruction?: string|Content,
     *          tools?: Tool|Tool[],
     *          toolConfig?: ToolConfig,
     *          safetySettings?: SafetySetting|SafetySetting[],
     *          generationConfig?: GenerationConfig,
     *          cachedContent?: string,
     *      },
     * } $parameters
     *
     * @return CountTokensResponse If successful.
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function countTokens(array $parameters): CountTokensResponse
    {
        /** @var CountTokensResponse */
        return $this->connector->send(new CountTokensRequest($parameters))->dtoOrFail();
    }

    /**
     * Generates an embedding from the model given an input `Content`.
     *
     * @link https://ai.google.dev/api/embeddings#v1beta.models.embedContent
     *
     * @param array{
     *      model: string,
     *      content: Content|string,
     *      taskType?: TaskType,
     *      title?: string,
     *      outputDimensionality?: int,
     *  } $parameters
     *
     * @return EmbedContentResponse If successful.
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function embedContent(array $parameters): EmbedContentResponse
    {
        /** @var EmbedContentResponse */
        return $this->connector->send(new EmbedContentRequest($parameters))->dtoOrFail();
    }

    /**
     * Generates an embedding from the model given an input message.
     *
     * @link https://ai.google.dev/api/palm#v1beta.models.embedText
     *
     * @param array{
     *      model: string,
     *      text: string,
     *  } $parameters
     *
     * @return EmbedTextResponse If successful.
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function embedText(array $parameters): EmbedTextResponse
    {
        /** @var EmbedTextResponse */
        return $this->connector->send(new EmbedTextRequest($parameters))->dtoOrFail();
    }

    /**
     * Generates a grounded answer from the model given an input.
     *
     * @link https://ai.google.dev/api/semantic-retrieval/question-answering#v1beta.models.generateAnswer
     *
     * @param array{
     *      model: string,
     *      contents: Content[],
     *      answerStyle: AnswerStyle,
     *      safetySettings?: SafetySetting|SafetySetting[],
     *      inlinePassages?: GroundingPassages,
     *      semanticRetriever?: SemanticRetrieverConfig,
     *      temperature?: float,
     *  } $parameters
     *
     * @return GenerateAnswerResponse If successful.
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function generateAnswer(array $parameters): GenerateAnswerResponse
    {
        /** @var GenerateAnswerResponse */
        return $this->connector->send(new GenerateAnswerRequest($parameters))->dtoOrFail();
    }

    /**
     * Generates a response from the model given an input
     *
     * @link https://ai.google.dev/api/generate-content#v1beta.models.generateContent
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
        return $this->connector->send(new GenerateContentRequest($parameters))->dtoOrFail();
    }

    /**
     * Generates a response from the model given an input MessagePrompt.
     *
     * @link https://ai.google.dev/api/palm#v1beta.models.generateMessage
     *
     * @param array{
     *      model: string,
     *      prompt: MessagePrompt,
     *      temperature?: float,
     *      candidateCount?: int,
     *      topP?: float,
     *      topK?: int,
     *  } $parameters
     *
     * @return GenerateMessageResponse If successful.
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function generateMessage(array $parameters): GenerateMessageResponse
    {
        /** @var GenerateMessageResponse */
        return $this->connector->send(new GenerateMessageRequest($parameters))->dtoOrFail();
    }

    /**
     * Generates a response from the model given an input message.
     *
     * @link https://ai.google.dev/api/palm#v1beta.models.generateText
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
        return $this->connector->send(new GenerateTextRequest($parameters))->dtoOrFail();
    }

    /**
     * Gets information about a specific Model.
     *
     * @link https://ai.google.dev/api/models#v1beta.models.get
     *
     * @param string $name The resource name of the model.
     *
     * @return Model If successful.
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function get(string $name): Model
    {
        /** @var Model */
        return $this->connector->send(new GetModelRequest($name))->dtoOrFail();
    }

    /**
     *Lists models available through the API.
     *
     * @link https://ai.google.dev/api/models#v1beta.models.list
     *
     * @param array{
     *     pageSize?: int,
     *     pageToken?: string,
     * } $parameters
     *
     * @return ListModelsResponse If successful.
     *
     * @throws FatalRequestException
     * @throws RequestException
     */
    public function list(array $parameters = []): ListModelsResponse
    {
        /** @var ListModelsResponse */
        return $this->connector->send(new ListModelsRequest($parameters))->dtoOrFail();
    }
}
