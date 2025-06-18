<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'openlibrary_id',
        'title',
        'authors',
        'description',
        'cover_image',
        'subjects',
        'page_count',
        'isbn',
        'publish_date',
        'average_rating',
        'ratings_count'
    ];

    protected $casts = [
        'authors' => 'array',
        'subjects' => 'array',
        'publish_date' => 'date'
    ];

    /**
     * NEW: A helper function to find a book by its Open Library ID or create it
     * from the API data if it doesn't exist in the local database.
     */
    public static function findOrCreateFromOpenLibrary(string $openLibraryId, \App\Services\OpenLibraryService $apiService): ?Book
    {
        $book = self::where('openlibrary_id', $openLibraryId)->first();

        if ($book) {
            return $book;
        }

        $details = $apiService->getBookDetails($openLibraryId);

        if (!$details) {
            return null; // Return null if API call fails
        }

        // Extract description, handling different possible formats from the API
        $description = null;
        if (isset($details['description'])) {
            $description = is_array($details['description']) ? ($details['description']['value'] ?? null) : $details['description'];
        }

        return self::create([
            'openlibrary_id' => $details['key'],
            'title' => $details['title'],
            'authors' => collect($details['authors'] ?? [])->map(function ($author) use ($apiService) {
                // The author data might be just a key, so we need to fetch author details if needed
                // This is a simplified version; a more robust implementation would fetch author names
                return $author['author']['key'] ?? 'Unknown Author';
            })->toArray(),
            'description' => $description,
            'cover_image' => $details['covers'][0] ?? null,
            'subjects' => $details['subjects'] ?? [],
            'page_count' => $details['number_of_pages'] ?? null,
            'publish_date' => isset($details['first_publish_date']) ? Carbon::parse($details['first_publish_date'])->toDateString() : null
        ]);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'book_categories');
    }

    public function userLibraries()
    {
        return $this->hasMany(UserLibrary::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getAuthorsStringAttribute()
    {
        return is_array($this->authors) ? implode(', ', $this->authors) : $this->authors;
    }

    public function getCoverUrlAttribute()
    {
        return $this->cover_image
            ? "https://covers.openlibrary.org/b/id/{$this->cover_image}-L.jpg"
            : asset('images/no-cover.jpg');
    }
}
