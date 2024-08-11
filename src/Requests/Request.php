<?php

namespace Derrickob\GeminiApi\Requests;

use Derrickob\GeminiApi\Enums\AuthMethod;
use Saloon\Http\Request as SaloonRequest;

abstract class Request extends SaloonRequest
{
    public AuthMethod $auth = AuthMethod::API_KEY;
}
