<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Responses\Models;

use Derrickob\GeminiApi\Contracts\ResponseContract;
use Derrickob\GeminiApi\Data\Candidate;
use Derrickob\GeminiApi\Data\InputFeedback;
use InvalidArgumentException;

final class GenerateAnswerResponse implements ResponseContract
{
    /**
     * @param Candidate     $answer                Candidate answer from the model.
     * @param int           $answerableProbability The model's estimate of the probability that its answer is correct and grounded in the input passages.
     * @param InputFeedback $inputFeedback         Feedback related to the input data used to answer the question, as opposed to the model-generated response to the question.
     */
    public function __construct(
        public readonly Candidate     $answer,
        public readonly int           $answerableProbability,
        public readonly InputFeedback $inputFeedback,
    ) {
        //
    }

    public static function fromArray(array $data): self
    {
        if (!isset($data['answer']) || !is_array($data['answer'])) {
            throw new InvalidArgumentException('answer must be an array');
        }

        if (!isset($data['answerableProbability']) || !is_int($data['answerableProbability'])) {
            throw new InvalidArgumentException('answerableProbability must be an integer');
        }

        if (!isset($data['inputFeedback']) || !is_array($data['inputFeedback'])) {
            throw new InvalidArgumentException('inputFeedback must be an array');
        }

        return new self(
            answer: Candidate::fromArray($data['answer']),
            answerableProbability: $data['answerableProbability'],
            inputFeedback: InputFeedback::fromArray($data['inputFeedback'])
        );
    }

    public function toArray(): array
    {
        return [
            'answer' => $this->answer->toArray(),
            'answerableProbability' => $this->answerableProbability,
            'inputFeedback' => $this->inputFeedback->toArray(),
        ];
    }
}
