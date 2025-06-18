<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
