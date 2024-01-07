<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Chapter;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $this->makeBook(1, 'Echoes of the Future');
        $this->makeBook(2, 'Lost Melodies: The Untold Stories of Forgotten Music');

    }

    protected function makeBook($bookNumber, string $title) : void
    {
        $book = File::get(storage_path('books/' .$bookNumber.'/intro.md'));

        $title = 'Echoes of the Future';

        $bookModel = Book::create([
            'title' => $title,
            'intro' => $book,
        ]);

        $path = storage_path('books/'.$bookNumber.'/chapters');

        foreach(File::allFiles($path) as $chapter) {
            $chapter = File::get($chapter);
            Chapter::create([
                'book_id' => $bookModel->id,
                'title' => str($chapter)
                    ->after("#")
                    ->before("\n")
                    ->trim()
                    ->toString(),
                'content' => $chapter
            ]);
        }
    }
}
