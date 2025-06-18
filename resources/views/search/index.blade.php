@extends('layouts.public')

@section('title', 'Search Results for "' . e($query) . '"')

@section('content')
    <div class="space-y-8">
        <section>
            @if ($query)
                <h1 class="text-3xl font-bold text-white mb-6">
                    Search Results for: <span class="text-purple-400">"{{ e($query) }}"</span>
                </h1>
            @else
                <h1 class="text-3xl font-bold text-white mb-6">
                    Please enter a search term
                </h1>
            @endif

            {{-- Handle case where query was submitted but no results were found --}}
            @if ($query && empty($books))
                <div class="text-center text-gray-500 py-16">
                    <p class="text-lg">No results found for your query.</p>
                    <p class="text-sm mt-2">Please try a different search term.</p>
                </div>
            @else
                @if (!empty($books))
                    <div class="mb-12">
                        <h2 class="text-2xl font-semibold text-white mb-4 border-b border-gray-700 pb-2">Books</h2>
                        <div
                            class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4 md:gap-6">
                            @foreach ($books as $item)
                                <x-api-content-card :item="$item" />
                            @endforeach
                        </div>
                    </div>
                @endif

            @endif
        </section>
    </div>
@endsection
