@extends('layouts.public')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Hero Section -->
        <div class="text-center py-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">
                Discover Your Next Great Read
            </h1>
            <p class="text-xl text-gray-600 mb-8">
                Access thousands of books from OpenLibrary and build your personal reading collection
            </p>

            <form action="{{ route('search.index') }}" method="GET" class="flex">
                <input type="text" name="q" placeholder="Search books..."
                    class="px-4 py-2 border rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    value="{{ request('q') }}">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-r-md hover:bg-blue-700">
                    Search
                </button>
            </form>
        </div>

        <!-- Categories -->
        @if ($categories->count() > 0)
            <section class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Browse by Category</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach ($categories as $category)
                        <a href="{{ route('search', ['category' => $category->slug]) }}"
                            class="p-4 bg-white rounded-lg shadow hover:shadow-md transition-shadow">
                            <h3 class="font-semibold text-gray-900">{{ $category->name }}</h3>
                            <p class="text-sm text-gray-600">{{ $category->books_count ?? 0 }} books</p>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        <!-- Featured Books -->
        @if ($featuredBooks->count() > 0)
            <section class="mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Featured Books</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
                    @foreach ($featuredBooks as $book)
                        <div class="bg-white rounded-lg shadow hover:shadow-md transition-shadow">
                            <div class="cursor-pointer">
                                @if (isset($book['cover_i']))
                                    <img src="https://covers.openlibrary.org/b/id/{{ $book['cover_i'] }}-M.jpg"
                                        alt="{{ $book['title'] ?? 'Book cover' }}"
                                        class="w-full h-48 object-cover rounded-t-lg">
                                @else
                                    <div class="w-full h-48 bg-gray-200 rounded-t-lg flex items-center justify-center">
                                        <span class="text-gray-500">No Cover</span>
                                    </div>
                                @endif
                                <div class="p-4">
                                    <h3 class="font-semibold text-sm text-gray-900 mb-1 line-clamp-2">
                                        {{ $book['title'] ?? 'Unknown Title' }}
                                    </h3>
                                    <p class="text-xs text-gray-600">
                                        {{ isset($book['author_name']) ? implode(', ', array_slice($book['author_name'], 0, 2)) : 'Unknown Author' }}
                                    </p>
                                    @if (isset($book['first_publish_year']))
                                        <p class="text-xs text-gray-500 mt-1">{{ $book['first_publish_year'] }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <!-- Recent Books -->
        @if ($recentBooks->count() > 0)
            <section>
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Recently Added</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($recentBooks as $book)
                        <div class="bg-white rounded-lg shadow hover:shadow-md transition-shadow p-4">
                            <div class="flex cursor-pointer">
                                @if (isset($book['cover_i']))
                                    <img src="https://covers.openlibrary.org/b/id/{{ $book['cover_i'] }}-S.jpg"
                                        alt="{{ $book['title'] ?? 'Book cover' }}"
                                        class="w-16 h-20 object-cover rounded mr-4">
                                @else
                                    <div class="w-16 h-20 bg-gray-200 rounded mr-4 flex items-center justify-center">
                                        <span class="text-xs text-gray-500">No Cover</span>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900 mb-1 line-clamp-2">
                                        {{ $book['title'] ?? 'Unknown Title' }}
                                    </h3>
                                    <p class="text-sm text-gray-600 mb-2">
                                        {{ isset($book['author_name']) ? implode(', ', array_slice($book['author_name'], 0, 2)) : 'Unknown Author' }}
                                    </p>
                                    @if (isset($book['first_publish_year']))
                                        <p class="text-xs text-gray-500">{{ $book['first_publish_year'] }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        @if ($categories->count() == 0 && $featuredBooks->count() == 0)
            <div class="text-center py-12">
                <h3 class="text-xl text-gray-600 mb-4">Welcome to your Online Book System!</h3>
                <p class="text-gray-500 mb-6">Start by searching for books or browse our categories.</p>
                <a href="{{ route('search') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                    Start Exploring Books
                </a>
            </div>
        @endif
    </div>
@endsection
