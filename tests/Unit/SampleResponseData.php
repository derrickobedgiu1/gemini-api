<?php

namespace Derrickob\GeminiApi\Tests\Unit;

class SampleResponseData
{
    public function default(): string
    {
        return '{"candidates":[{"content":{"parts":[{"text":"sample reply"}],"role":"model"},"finishReason":"STOP","index":0,"safetyRatings":[{"category":"HARM_CATEGORY_SEXUALLY_EXPLICIT","probability":"NEGLIGIBLE"},{"category":"HARM_CATEGORY_HATE_SPEECH","probability":"NEGLIGIBLE"},{"category":"HARM_CATEGORY_HARASSMENT","probability":"NEGLIGIBLE"},{"category":"HARM_CATEGORY_DANGEROUS_CONTENT","probability":"NEGLIGIBLE"}]}]}';
    }

    public function empty(): string
    {
        return '{}';
    }

    public function tokens(): string
    {
        return '{"totalTokens": 10}';
    }

    public function model(): string
    {
        return '{"name":"models/gemini-pro","version":"001","displayName":"Gemini 1.0 Pro","description":"The best model for scaling across a wide range of tasks","inputTokenLimit":30720,"outputTokenLimit":2048,"supportedGenerationMethods":["generateContent","countTokens"],"temperature":0.9,"topP":1,"topK":1}';
    }

    public function file(): string
    {
        return '{"name":"files/the-id","displayName":"some text","mimeType":"audio/mpeg","sizeBytes":"3235977","createTime":"2024-04-18T10:13:48.560439Z","updateTime":"2024-04-18T10:13:48.560439Z","expirationTime":"2024-04-20T10:13:48.544708169Z","sha256Hash":"Mjkgh==","uri":"https://generativelanguage.googleapis.com/v1beta/files/welmj2l0d491"}';
    }

    public function tuned(): string
    {
        return '{"name":"tunedModels/...","baseModel":"...","displayName":"...","state":"...","createTime":"...","updateTime":"...","tuningTask":{"startTime":"...","completeTime":"...","snapshots":[{"step":1,"meanLoss":11.499258,"computeTime":"..."},{"step":2,"meanLoss":13.745591,"computeTime":"..."}],"hyperparameters":{"epochCount":5,"batchSize":2,"learningRate":0.001}},"temperature":0.9,"topP":1,"topK":1}';
    }

    public function embed(): string
    {
        return '{"embedding":{"values":[0.013168523,-0.008711934,0.04019695]}}';
    }
}
