<?php

return [

    /*
    |--------------------------------------------------------------------------
    | OpenAI API Key and Organization
    |--------------------------------------------------------------------------
    |
    | Here you may specify your OpenAI API Key and organization. This will be
    | used to authenticate with the OpenAI API - you can find your API key
    | and organization on your OpenAI dashboard, at https://openai.com.
    */

    'api_key' => env('OPENAI_API_KEY', 'fake_key'),
    'organization' => env('OPENAI_ORGANIZATION'),
    'mock' => env('OPENAI_MOCK', true),
    'chat_system_default_message' => "You are an assistant helping the user understand the data provided",
    'chat_model' => env('OPENAI_CHAT_CLIENT_MODEL', 'gpt-3.5-turbo-16k'),
    'chat_model_with_function' => env('OPENAI_CHAT_CLIENT_MODEL_WITH_FUNCTIONS', 'gpt-3.5-turbo-16k'),
    'temperature' => env('OPENAI_TEMPERATURE', 0.5),
    'assistant' => env('OPEN_AI_ASSISTANT_ID'),
    'shopping_assistant' => env('OPEN_AI_SHOPPING_ASSISTANT_ID'),
    'max_completion_size' => env('OPENAI_MAX_COMPLETION_SIZE', 8000),
    'max_question_size' => env('OPENAI_MAX_QUESTION_SIZE', 8000),
    'max_response_size' => env('OPENAI_MAX_RESPONSE_SIZE', 1000),
    'request_timeout' => env('OPENAI_REQUEST_TIMEOUT', 120),
];
