@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Search Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">
                @if ($query)
                    Search Results for "{{ $query }}"
                @elseif($category)
                    Books in {{ ucfirst(str_replace('-', ' ', $category)) }}
                @else
                    Search Books
                @endif
            </h1>

            <!-- Search Form -->
            <form action="{{ route('search') }}" method="GET" class="mb-6">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" name="q" value="{{ $query }}"
                            placeholder="Search for books, authors, or subjects..."
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div class="flex gap-2">
                        <select name="category"
                            class="px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All Categories</option>
                            <option value="fiction" {{ $category == 'fiction' ? 'selected' : '' }}>Fiction</option>
                            <option value="non-fiction" {{ $category == 'non-fiction' ? 'selected' : '' }}>Non-Fiction
                            </option>
                            <option value="science" {{ $category == 'science' ? 'selected' : '' }}>Science</option>
                            <option value="history" {{ $category == 'history' ? 'selected' : '' }}>History</option>
                            <option value="romance" {{ $category == 'romance' ? 'selected' : '' }}>Romance</option>
                            <option value="mystery" {{ $category == 'mystery' ? 'selected' : '' }}>Mystery</option>
                            <option value="fantasy" {{ $category == 'fantasy' ? 'selected' : '' }}>Fantasy</option>
                            <option value="biography" {{ $category == 'biography' ? 'selected' : '' }}>Biography</option>
                        </select>
                        <button type="submit"
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Search
                        </button>
                    </div>
                </div>
            </form>

            <!-- Results Info -->
            @if ($query || $category)
                <div class="text-gray-600 mb-4">
                    @if ($total > 0)
                        Showing {{ count($books) }} of {{ number_format($total) }} results
                    @else
                        No results found
                    @endif
                </div>
            @endif
        </div>

        <!-- Search Results -->
        @if (count($books) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                @foreach ($books as $book)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
                        <div class="p-4">
                            <!-- Book Cover -->
                            <div class="mb-4">
                                @if (isset($book['cover_i']))
                                    <img src="https://covers.openlibrary.org/b/id/{{ $book['cover_i'] }}-M.jpg"
                                        alt="{{ $book['title'] ?? 'Book cover' }}"
                                        class="w-full h-48 object-cover rounded-lg">
                                @else
                                    <div class="w-full h-48 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <div class="text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                            </svg>
                                            <p class="text-sm text-gray-500 mt-2">No Cover</p>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Book Info -->
                            <div class="space-y-2">
                                <h3 class="font-semibold text-gray-900 line-clamp-2 text-sm">
                                    {{ $book['title'] ?? 'Unknown Title' }}
                                </h3>

                                <p class="text-sm text-gray-600">
                                    @if (isset($book['author_name']) && is_array($book['author_name']))
                                        {{ implode(', ', array_slice($book['author_name'], 0, 2)) }}
                                        @if (count($book['author_name']) > 2)
                                            <span class="text-gray-400">and {{ count($book['author_name']) - 2 }}
                                                more</span>
                                        @endif
                                    @else
                                        Unknown Author
                                    @endif
                                </p>

                                @if (isset($book['first_publish_year']))
                                    <p class="text-xs text-gray-500">Published: {{ $book['first_publish_year'] }}</p>
                                @endif

                                @if (isset($book['subject']) && is_array($book['subject']))
                                    <div class="flex flex-wrap gap-1 mt-2">
                                        @foreach (array_slice($book['subject'], 0, 3) as $subject)
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                                {{ $subject }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <!-- Action Buttons -->
                            <div class="mt-4 space-y-2">
                                @if (isset($book['key']))
                                    <a href="{{ route('books.show', ['id' => urlencode($book['key'])]) }}"
                                        class="w-full bg-blue-600 text-white text-center py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors duration-200 block">
                                        View Details
                                    </a>
                                @endif

                                @auth
                                    <form action="{{ route('books.add-to-library', ['id' => urlencode($book['key'] ?? '')]) }}"
                                        method="POST" class="inline-block w-full">
                                        @csrf
                                        <input type="hidden" name="status" value="want_to_read">
                                        <button type="submit"
                                            class="w-full bg-gray-100 text-gray-700 text-center py-2 px-4 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                                            Add to Library
                                        </button>
                                    </form>
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if ($total > count($books))
                <div class="flex justify-center items-center space-x-4">
                    @if ($page > 1)
                        <a href="{{ route('search', array_merge(request()->query(), ['page' => $page - 1])) }}"
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                            Previous
                        </a>
                    @endif

                    <span class="text-gray-600">
                        Page {{ $page }} of {{ ceil($total / $limit) }}
                    </span>

                    @if ($page * $limit < $total)
                        <a href="{{ route('search', array_merge(request()->query(), ['page' => $page + 1])) }}"
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                            Next
                        </a>
                    @endif
                </div>
            @endif
        @elseif($query || $category)
            <!-- No Results -->
            <div class="text-center py-12">
                <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.47-.881-6.08-2.33l-.147-.15A7.97 7.97 0 013 12.024c0-1.654.5-3.19 1.357-4.466L4.5 7.5" />
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No books found</h3>
                <p class="mt-2 text-gray-500">
                    @if ($query)
                        No books found for "{{ $query }}". Try different keywords or browse categories.
                    @else
                        No books found in this category. Try a different category or search term.
                    @endif
                </p>
                <div class="mt-6">
                    <a href="{{ route('home') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                        Back to Home
                    </a>
                </div>
            </div>
        @else
            <!-- Search Landing -->
            <div class="text-center py-12">
                <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">Search for Books</h3>
                <p class="mt-2 text-gray-500">Enter a search term above to find books, authors, or subjects.</p>

                <!-- Popular Search Suggestions -->
                <div class="mt-8">
                    <h4 class="text-sm font-medium text-gray-900 mb-4">Popular Searches:</h4>
                    <div class="flex flex-wrap justify-center gap-2">
                        @foreach (['fiction', 'science', 'history', 'romance', 'mystery', 'biography'] as $suggestion)
                            <a href="{{ route('search', ['category' => $suggestion]) }}"
                                class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm hover:bg-blue-200">
                                {{ ucfirst($suggestion) }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
