<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Services\OpenLibraryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    private $openLibraryService;

    public function __construct(OpenLibraryService $openLibraryService)
    {
        $this->openLibraryService = $openLibraryService;
    }

    public function index()
    {
        // Get categories with book count, or empty collection if none exist
        $categories = Category::withCount('books')->take(8)->get();

        // If no categories exist, create some default ones
        if ($categories->isEmpty()) {
            $this->createDefaultCategories();
            $categories = Category::withCount('books')->take(8)->get();
        }

        // Use empty collections as fallback
        $featuredBooks = collect([]);
        $recentBooks = collect([]);

        // Try to get books from OpenLibrary API
        try {
            $apiBooks = $this->openLibraryService->searchBooks('popular', 12);
            $featuredBooks = collect($apiBooks['docs'] ?? []);

            $recentApiBooks = $this->openLibraryService->searchBooks('fiction', 8);
            $recentBooks = collect($recentApiBooks['docs'] ?? []);
        } catch (\Exception $e) {
            // Log error but continue with empty collections
            Log::error('OpenLibrary API Error: ' . $e->getMessage());
        }

        return view('home', compact('categories', 'featuredBooks', 'recentBooks'));
    }
    private function createDefaultCategories()
    {
        $categories = [
            ['name' => 'Fiction', 'slug' => 'fiction', 'description' => 'Fictional stories and novels'],
            ['name' => 'Non-Fiction', 'slug' => 'non-fiction', 'description' => 'Real-world topics and factual content'],
            ['name' => 'Science', 'slug' => 'science', 'description' => 'Scientific books and research'],
            ['name' => 'History', 'slug' => 'history', 'description' => 'Historical events and biographies'],
            ['name' => 'Romance', 'slug' => 'romance', 'description' => 'Love stories and romantic novels'],
            ['name' => 'Mystery', 'slug' => 'mystery', 'description' => 'Mystery and thriller books'],
            ['name' => 'Fantasy', 'slug' => 'fantasy', 'description' => 'Fantasy and magical stories'],
            ['name' => 'Biography', 'slug' => 'biography', 'description' => 'Life stories of notable people'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
