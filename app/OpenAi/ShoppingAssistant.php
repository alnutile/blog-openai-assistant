<?php

namespace App\OpenAi;

use App\Models\Assistant as AssistantModel;
use App\Models\Book;
use App\Models\Chapter;
use App\Models\Chat;
use Illuminate\Support\Facades\Storage;
use OpenAI\Laravel\Facades\OpenAI;
use OpenAI\Responses\Threads\Runs\ThreadRunListResponse;
use OpenAI\Responses\Threads\Runs\ThreadRunResponse;

class ShoppingAssistant
{

    protected Chat $chat;

    public function startOrGetThread(Chat $chat) : void
    {
        $this->chat = $chat;

        if(is_null($chat->thread_id)) {
            $response = OpenAI::threads()->create([]);

            $this->chat->update(['thread_id' => $response->id]);

            $path = storage_path('chat/amazon_orders_2023_sample.csv');
            $this->uploadFile($path);

            OpenAI::threads()->runs()->create(
                threadId: $this->chat->getThreadId(),
                parameters: [
                    'assistant_id' => $this->getAssistantId()
                ]
            );
        }
    }

    public function getRuns(Chat $chat)  : ThreadRunListResponse
    {
        $this->startOrGetThread($chat);

        return OpenAI::threads()->runs()->list(
            threadId: $this->chat->thread_id,
            parameters: [
                'limit' => 10
            ]
        );
    }

    public function getMessages(Chat $chat)
    {
        $this->startOrGetThread($chat);

        return OpenAI::threads()->messages()->list(
                threadId: $this->chat->thread_id,
                parameters: [
                    'limit' => 10
                ]
            );
    }

    public function getRun(Chat $chat, string $runId) : ThreadRunResponse
    {
        $this->startOrGetThread($chat);

        return OpenAI::threads()->runs()->retrieve(
            threadId: $this->chat->thread_id,
            runId: $runId,
        );
    }

    public function getImage(Chat $chat, string $fileId): string
    {

        $this->startOrGetThread($chat);

        $response = OpenAI::files()->download(file: $fileId);

        Storage::disk('chat')->put($fileId . '.png', $response);

        return $response;
    }

    public function askForAssistants(Chat $chat, string $input) : ThreadRunResponse
    {
        $this->startOrGetThread($chat);

        OpenAI::threads()
            ->messages()
            ->create(
                $this->chat->thread_id,
                parameters: [
                    'role' => 'user',
                    'content' => $input
                ]
            );

        return OpenAI::threads()->runs()->create(
            threadId: $this->chat->thread_id,
            parameters: [
                'assistant_id' => $this->getAssistantId()
            ]
        );

    }

    protected function getAssistantId()
    {
        $assistantId = config('openai.shopping_assistant');

        if(is_null($assistantId)) {
            throw new \Exception("Missing assistant id");
        }

        return $assistantId;
    }

    public function uploadFile(string $pathToFile)
    {
        $response = OpenAI::files()->upload(
            [
                'purpose' => 'assistants',
                'file' => fopen($pathToFile, 'r'),
            ],
        );

        OpenAI::threads()->messages()->create(
            threadId: $this->chat->thread_id,
            parameters:  [
                'role' => 'user',
                'file_ids' => [$response->id],
                'content' => "I am uploading a file to show my amazon shopping history"
            ]
        );

        return OpenAI::threads()->runs()->create(
            threadId: $this->chat->thread_id,
            parameters: [
                'assistant_id' => $this->getAssistantId(),
            ]
        );
    }
}
