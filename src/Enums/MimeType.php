<?php

declare(strict_types=1);

namespace Derrickob\GeminiApi\Enums;

enum MimeType: string
{
    // Image formats
    case IMAGE_PNG = 'image/png';
    case IMAGE_JPEG = 'image/jpeg';
    case IMAGE_HEIC = 'image/heic';
    case IMAGE_HEIF = 'image/heif';
    case IMAGE_WEBP = 'image/webp';

    // Audio formats
    case AUDIO_WAV = 'audio/wav';
    case AUDIO_MP3 = 'audio/mp3';
    case AUDIO_AIFF = 'audio/aiff';
    case AUDIO_AAC = 'audio/aac';
    case AUDIO_OGG = 'audio/ogg';
    case AUDIO_FLAC = 'audio/flac';

    // Video formats
    case VIDEO_MP4 = 'video/mp4';
    case VIDEO_MPEG = 'video/mpeg';
    case VIDEO_MOV = 'video/mov';
    case VIDEO_AVI = 'video/avi';
    case VIDEO_FLV = 'video/x-flv';
    case VIDEO_MPG = 'video/mpg';
    case VIDEO_WEBM = 'video/webm';
    case VIDEO_WMV = 'video/wmv';
    case VIDEO_3GPP = 'video/3gpp';

    // Text formats
    case APP_PDF = 'application/pdf';
    case TEXT_PLAIN = 'text/plain';
    case TEXT_HTML = 'text/html';
    case TEXT_CSS = 'text/css';
    case TEXT_JAVASCRIPT = 'text/javascript';
    case APP_JAVASCRIPT = 'application/x-javascript';
    case TEXT_TYPESCRIPT = 'text/x-typescript';
    case APP_TYPESCRIPT = 'application/x-typescript';
    case TEXT_CSV = 'text/csv';
    case TEXT_MARKDOWN = 'text/markdown';
    case TEXT_PYTHON = 'text/x-python';
    case APP_PYTHON = 'application/x-python-code';
    case APP_JSON = 'application/json';
    case TEXT_XML = 'text/xml';
    case TEXT_RTF = 'text/rtf';
    case APP_RTF = 'application/rtf';
}
