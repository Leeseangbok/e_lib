<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OpenLibraryService;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
    protected $open_library_service;

    public function __construct(OpenLibraryService $open_library_service)
    {
        $this->open_library_service = $open_library_service;
    }

    /**
     * Handle the incoming search request using the TMDB API.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = $request->input('q');
        $page = $request->input('page', 1);

        $moviesResponse = [];
        $tvShowsResponse = [];

        if ($query) {
            $moviesResponse = $this->open_library_service->searchBooks($query, $page);
        }

        // For simplicity, we'll paginate one or the other, or combine.
        // Let's combine and manually create a simple paginator if needed,
        // or just show top results without pagination for now.
        // The API returns paginated results, but combining them is complex.
        // We'll show top movies and top TV shows on the same page.

        return view('search.index', [
            'query' => $query,
            'movies' => $moviesResponse['results'] ?? [],
            'tvShows' => $tvShowsResponse['results'] ?? [],
        ]);
    }
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $category = $request->get('category', '');
        $page = (int) $request->get('page', 1);
        $limit = 20;
        $offset = ($page - 1) * $limit;

        $books = [];
        $total = 0;

        try {
            if ($category && !$query) {
                // Search by category/subject
                $results = $this->open_library_service->getBooksBySubject($category, $limit, $offset);
                $books = $results['works'] ?? [];
                $total = count($books); // OpenLibrary doesn't provide total count for subjects
            } elseif ($query) {
                // Search by query
                $searchQuery = $query;
                if ($category) {
                    $searchQuery .= " subject:" . $category;
                }
                $results = $this->open_library_service->searchBooks($searchQuery, $limit, $offset);
                $books = $results['docs'] ?? [];
                $total = $results['numFound'] ?? 0;
            } else {
                // Default popular books if no search criteria
                $results = $this->open_library_service->searchBooks('popular', $limit, $offset);
                $books = $results['docs'] ?? [];
                $total = $results['numFound'] ?? 0;
            }
        } catch (\Exception $e) {
            Log::error('Search Error: ' . $e->getMessage());
            $books = [];
            $total = 0;
        }

        return view('search', compact('books', 'query', 'category', 'total', 'page', 'limit'));
    }
}
