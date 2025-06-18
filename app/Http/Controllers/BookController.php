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

    public function show($id)
    {
        $book = Book::with(['reviews.user', 'categories'])->findOrFail($id);

        $userLibrary = null;
        if (Auth::check()) {
            $userLibrary = UserLibrary::where('user_id', Auth::id())
                ->where('book_id', $book->id)
                ->first();
        }

        $relatedBooks = Book::whereHas('categories', function ($query) use ($book) {
            $query->whereIn('categories.id', $book->categories->pluck('id'));
        })->where('id', '!=', $book->id)->take(6)->get();

        return view('books.show', compact('book', 'userLibrary', 'relatedBooks'));
    }

    public function read($id)
    {
        $book = Book::findOrFail($id);

        if (Auth::check()) {
            UserLibrary::updateOrCreate(
                ['user_id' => Auth::id(), 'book_id' => $book->id],
                ['status' => 'reading', 'last_read_at' => now()]
            );
        }

        // In a real implementation, you'd fetch the book content
        // For now, we'll show a placeholder reading interface
        return view('books.read', compact('book'));
    }

    public function addToLibrary(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:reading,completed,want_to_read,favorite'
        ]);

        $book = Book::findOrFail($id);

        UserLibrary::updateOrCreate(
            ['user_id' => Auth::id(), 'book_id' => $book->id],
            ['status' => $request->status, 'last_read_at' => now()]
        );

        return redirect()->back()->with('success', 'Book added to your library!');
    }

    public function updateProgress(Request $request, $id)
    {
        $request->validate([
            'current_page' => 'required|integer|min:0'
        ]);

        $userLibrary = UserLibrary::where('user_id', Auth::id())
            ->where('book_id', $id)
            ->firstOrFail();

        $userLibrary->update([
            'current_page' => $request->current_page,
            'last_read_at' => now()
        ]);

        return response()->json(['success' => true]);
    }
}
