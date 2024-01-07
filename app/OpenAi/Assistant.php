<?php

namespace App\OpenAi;

use App\Models\Book;
use App\Models\Chapter;
use OpenAI\Laravel\Facades\OpenAI;
use OpenAI\Responses\Threads\Runs\ThreadRunListResponse;
use OpenAI\Responses\Threads\Runs\ThreadRunResponse;

class Assistant
{

    protected Book $book;

    public function startOrGetThread(Book $book) : void
    {
        $this->book = $book;

        if(is_null($book->thread_id)) {
            $response = OpenAI::threads()->create([]);

            $this->book->update(['thread_id' => $response->id]);

            $this->uploadChapters();

            OpenAI::threads()->runs()->create(
                threadId: $this->book->thread_id,
                parameters: [
                    'assistant_id' => $this->getAssistantId()
                ]
            );
        }
    }

    public function getRuns(Book $book)  : ThreadRunListResponse
    {
        $this->startOrGetThread($book);

        return OpenAI::threads()->runs()->list(
            threadId: $this->book->thread_id,
            parameters: [
                'limit' => 10
            ]
        );
    }

    public function getMessages(Book $book)
    {
        $this->startOrGetThread($book);

        return OpenAI::threads()->messages()->list(
                threadId: $this->book->thread_id,
                parameters: [
                    'limit' => 10
                ]
            );
    }

    public function getRun(Book $book, string $runId) : ThreadRunResponse
    {
        $this->startOrGetThread($book);

        return OpenAI::threads()->runs()->retrieve(
            threadId: $this->book->thread_id,
            runId: $runId,
        );
    }

    public function askForAssistants(Book $book, string $input) : ThreadRunResponse
    {
        $this->startOrGetThread($book);

        OpenAI::threads()
            ->messages()
            ->create(
                $this->book->thread_id,
                parameters: [
                    'role' => 'user',
                    'content' => $input
                ]
            );

        return OpenAI::threads()->runs()->create(
            threadId: $this->book->thread_id,
            parameters: [
                'assistant_id' => $this->getAssistantId()
            ]
        );

    }

    protected function getAssistantId()
    {
        $assistantId = config('openai.assistant');

        if(is_null($assistantId)) {
            throw new \Exception("Missing assistant id");
        }

        return $assistantId;
    }

    protected function uploadChapters()
    {
        /** @var Chapter $chapter */
        foreach($this->book->chapters as $chapter) {
            $content = <<<EOD
Previous Chapter: $chapter->id $chapter->title
Content:
$chapter->content
EOD;

            OpenAI::threads()
                ->messages()
                ->create(
                    $this->book->thread_id,
                    parameters: [
                        'role' => 'user',
                        'content' => $content
                    ]
                );
        }
    }

    public function uploadFile(string $pathToFile)
    {
        //coming next
    }
}
