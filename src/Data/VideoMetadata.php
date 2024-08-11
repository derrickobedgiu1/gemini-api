<?php

namespace Derrickob\GeminiApi\Data;

final class VideoMetadata
{
    /**
     * @param string $videoDuration Duration of the video.
     */
    public function __construct(public readonly string $videoDuration)
    {
        //
    }

    public function toArray(): array
    {
        return [
            'videoDuration' => $this->videoDuration,
        ];
    }
}
