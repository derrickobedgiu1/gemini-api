<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Contracts\Resources;

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

interface ModelContract
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
    public function batchEmbedContents(array $parameters): BatchEmbedContentsResponse;

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
    public function batchEmbedText(array $parameters): BatchEmbedTextResponse;

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
    public function countMessageTokens(array $parameters): CountMessageTokensResponse;

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
    public function countTextTokens(array $parameters): CountTextTokensResponse;

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
    public function countTokens(array $parameters): CountTokensResponse;

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
    public function embedContent(array $parameters): EmbedContentResponse;

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
    public function embedText(array $parameters): EmbedTextResponse;

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
    public function generateAnswer(array $parameters): GenerateAnswerResponse;

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
    public function generateContent(array $parameters): GenerateContentResponse;

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
    public function generateMessage(array $parameters): GenerateMessageResponse;

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
    public function generateText(array $parameters): GenerateTextResponse;

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
    public function get(string $name): Model;

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
    public function list(array $parameters = []): ListModelsResponse;
}
