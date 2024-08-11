# Additional Examples

## Code Execution

```php
use Derrickob\GeminiApi\Data\CodeExecution;
use Derrickob\GeminiApi\Data\Content;
use Derrickob\GeminiApi\Data\Tool;

$response = $gemini->models()->generateContent([
    'model' => 'models/gemini-1.5-flash',
    'contents' => Content::createTextContent('What is the sum of the first 50 prime numbers? Generate and run code for the calculation, and make sure you get all 50.', 'user'),
    'tools' => new Tool(codeExecution: new CodeExecution()),
]);

echo $response->text();
```

## JSON Mode

```php
use Derrickob\GeminiApi\Data\GenerationConfig;
use Derrickob\GeminiApi\Data\Schema;

$responseSchema = [
    'type' => 'array',
    'items' => [
        'type' => 'object',
        'properties' => [
            'productName' => [
                'type' => 'string',
            ],
        ],
    ],
];

$response = $gemini->models()->generateContent([
    'model' => 'models/gemini-1.5-flash',
    'contents' => 'Give me 5 names for my product, it\'s a bottle of tea',
    'generationConfig' => new GenerationConfig(
        responseMimeType: 'application/json',
        responseSchema: Schema::fromArray($responseSchema)
    ),
]);

echo $response->text(); // [{"productName": "Tranquility Tea"}, {"productName": "Golden Hour Brew"}, {"productName": "Serene Sip"}, {"productName": "Zenith Infusion"}, {"productName": "Midnight Bloom"}]
```

## Function Calling

### Request
The example below utilizes a simplified interface with the basic parameters commonly used. You can construct the full request manually to have full control.

```php
use Derrickob\GeminiApi\Data\Content;
use Derrickob\GeminiApi\Data\FunctionCallingConfig;
use Derrickob\GeminiApi\Data\FunctionDeclaration;
use Derrickob\GeminiApi\Data\Tool;
use Derrickob\GeminiApi\Data\ToolConfig;
use Derrickob\GeminiApi\Enums\Mode;

$functionDeclarations = [
    FunctionDeclaration::generate(
        name: 'find_movies',
        description: 'find movie titles currently playing in theaters based on any description, genre, title words, etc.',
        params: [
            'location' => [
                'type' => 'string',
                'description' => 'The city and state, e.g. San Francisco, CA or a zip code e.g. 95616',
            ],
            'description' => [
                'type' => 'string',
                'description' => 'Any kind of description including category or genre, title words, attributes, etc.',
            ],
        ],
        required: ['description']
    ),
    FunctionDeclaration::generate(
        name: 'find_theaters',
        description: 'find theaters based on location and optionally movie title which is currently playing in theaters',
        params: [
            'location' => [
                'type' => 'string',
                'description' => 'The city and state, e.g. San Francisco, CA or a zip code e.g. 95616',
            ],
            'movie' => [
                'type' => 'string',
                'description' => 'Any movie title',
            ],
        ],
        required: ['location']
    ),
    FunctionDeclaration::generate(
        name: 'get_showtimes',
        description: 'Find the start times for movies playing in a specific theater',
        params: [
            'location' => [
                'type' => 'string',
                'description' => 'The city and state, e.g. San Francisco, CA or a zip code e.g. 95616',
            ],
            'movie' => [
                'type' => 'string',
                'description' => 'Any movie title',
            ],
            'theater' => [
                'type' => 'string',
                'description' => 'Name of the theater',
            ],
            'date' => [
                'type' => 'string',
                'description' => 'Date for requested showtime',
            ],
        ],
        required: ['location', 'movie', 'theater', 'date']
    ),
];

$response = $gemini->models()->generateContent([
    'model' => 'models/gemini-1.5-flash',
    'contents' => Content::createTextContent('What movies are showing in North Seattle tonight?', 'user'),
    'tools' => new Tool($functionDeclarations),
    'toolConfig' => new ToolConfig(
        new FunctionCallingConfig(
            mode: Mode::AUTO
        ),
    ),
]);


# Two ways to access the function call, choose convenient one:

# Version 1: Using the Struct
$functionCall = $response->functionCall();
$functionName = $functionCall->name;
$functionArgs = $functionCall->args;

// Assuming you received find_movies which has location & description parameters
$location = $functionArgs->getFields()['location']->getStringValue();
$description = $functionArgs->getFields()['description']->getStringValue();


# Version 2: Convert the response to array
$functionCall = $response->functionCall()->toArray();
$functionName = $functionCall['name'];
$functionArgs = $functionCall['args'];
```

### Submit Function Call Response

```php
use Derrickob\GeminiApi\Data\Content;
use Derrickob\GeminiApi\Data\FunctionCallingConfig;
use Derrickob\GeminiApi\Data\FunctionDeclaration;
use Derrickob\GeminiApi\Data\Tool;
use Derrickob\GeminiApi\Data\ToolConfig;
use Derrickob\GeminiApi\Enums\Mode;

$functionDeclarations = [
    FunctionDeclaration::generate(
        name: 'find_movies',
        description: 'find movie titles currently playing in theaters based on any description, genre, title words, etc.',
        params: [
            'location' => [
                'type' => 'string',
                'description' => 'The city and state, e.g. San Francisco, CA or a zip code e.g. 95616',
            ],
            'description' => [
                'type' => 'string',
                'description' => 'Any kind of description including category or genre, title words, attributes, etc.',
            ],
        ],
        required: ['description']
    ),
    FunctionDeclaration::generate(
        name: 'find_theaters',
        description: 'find theaters based on location and optionally movie title which is currently playing in theaters',
        params: [
            'location' => [
                'type' => 'string',
                'description' => 'The city and state, e.g. San Francisco, CA or a zip code e.g. 95616',
            ],
            'movie' => [
                'type' => 'string',
                'description' => 'Any movie title',
            ],
        ],
        required: ['location']
    ),
    FunctionDeclaration::generate(
        name: 'get_showtimes',
        description: 'Find the start times for movies playing in a specific theater',
        params: [
            'location' => [
                'type' => 'string',
                'description' => 'The city and state, e.g. San Francisco, CA or a zip code e.g. 95616',
            ],
            'movie' => [
                'type' => 'string',
                'description' => 'Any movie title',
            ],
            'theater' => [
                'type' => 'string',
                'description' => 'Name of the theater',
            ],
            'date' => [
                'type' => 'string',
                'description' => 'Date for requested showtime',
            ],
        ],
        required: ['location', 'movie', 'theater', 'date']
    ),
];

$contents = [
    Content::createTextContent('Which theaters in Mountain View show Barbie movie?', 'user'),
    Content::createFunctionCallContent(
        'find_theaters',
        ['location' => 'Mountain View, CA', 'movie' => 'Barbie']
    ),
    Content::createFunctionResponseContent(
        'find_theaters',
        [
            'movie' => 'Barbie',
            'theaters' => [
                ['name' => 'AMC Mountain View 16', 'address' => '2000 W El Camino Real, Mountain View, CA 94040'],
                ['name' => 'Regal Edwards 14', 'address' => '245 Castro St, Mountain View, CA 94040'],
            ],
        ]
    ),
];

$response = $gemini->models()->generateContent([
    'model' => 'models/gemini-1.5-flash',
    'contents' => $contents,
    'tools' => new Tool($functionDeclarations),
    'toolConfig' => new ToolConfig(
        new FunctionCallingConfig(
            mode: Mode::AUTO
        ),
    ),
]);

echo $response->text(); // The following theaters in Mountain View sh...
```