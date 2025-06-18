<?php

namespace App\Http\Controllers;

use App\Models\UserLibrary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\OpenLibraryService;

class LibraryController extends Controller
{
    protected $OpenLibraryService;

    public function __construct(OpenLibraryService $OpenLibraryService)
    {
        $this->OpenLibraryService = $OpenLibraryService;
    }

    public function index(Request $request)
    {
        $status = $request->get('status', 'all');

        $query = UserLibrary::with('book')
            ->where('user_id', Auth::id());

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $books = $query->orderBy('last_read_at', 'desc')
            ->paginate(12);

        return view('library.index', compact('books', 'status'));
    }

    public function favorites()
    {
        $favorites = UserLibrary::with('book')
            ->where('user_id', Auth::id())
            ->where('status', 'favorite')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('library.favorites', compact('favorites'));
    }

    public function remove($id)
    {
        UserLibrary::where('user_id', Auth::id())
            ->where('book_id', $id)
            ->delete();

        return redirect()->back()->with('success', 'Book removed from library!');
    }
}
