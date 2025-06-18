<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\UserLibrary;
use App\Services\OpenLibraryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    private $openLibraryService;

    public function __construct(OpenLibraryService $openLibraryService)
    {
        $this->openLibraryService = $openLibraryService;
    }

    // ... (show method remains the same)

    // MODIFIED: Update the 'read' method to fetch and pass progress
    public function read($openLibraryId)
    {

        $openLibraryId = urldecode($openLibraryId);
        $book = Book::findOrCreateFromOpenLibrary($openLibraryId, $this->openLibraryService);

        if (!$book) {
            abort(404);
        }

        // Get or create the user's library entry for this book
        $userLibrary = UserLibrary::firstOrCreate(
            ['user_id' => Auth::id(), 'book_id' => $book->id],
            ['status' => 'reading'] // Default to 'reading' if it's a new entry
        );

        // If the book was in the library but not as 'reading', update it
        if ($userLibrary->status !== 'reading') {
            $userLibrary->status = 'reading';
        }

        $userLibrary->last_read_at = now();
        $userLibrary->save();

        return view('books.read', compact('book', 'userLibrary'));
    }

    // ... (other methods remain the same)
}
