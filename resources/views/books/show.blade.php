@extends('layouts.public')

@section('title', $book->title)

@section('content')
    <div class="container mx-auto">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden md:flex">
            <div class="md:w-1/3">
                <img src="{{ $book->getCoverUrlAttribute() }}" alt="Cover of {{ $book->title }}"
                    class="w-full h-auto object-cover">
            </div>

            <div class="md:w-2/3 p-6 flex flex-col justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $book->title }}</h1>
                    <p class="text-lg text-gray-700 mb-2">by {{ $book->authors_string }}</p>
                    <p class="text-sm text-gray-500 mb-4">
                        Published: {{ $book->publish_date ? $book->publish_date->format('Y') : 'N/A' }}
                    </p>

                    <div class="prose max-w-none text-gray-600 mb-6">
                        {!! nl2br(e($book->description)) !!}
                    </div>

                    @if ($book->subjects)
                        <div class="mb-6">
                            <h3 class="font-semibold text-gray-800 mb-2">Genres</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach (array_slice($book->subjects, 0, 5) as $subject)
                                    <span
                                        class="px-3 py-1 bg-gray-200 text-gray-800 text-sm rounded-full">{{ $subject }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                @auth
                    <div class="flex items-center space-x-4">
                        <form
                            action="{{ route('books.add-to-library', ['openLibraryId' => urlencode($book->openlibrary_id)]) }}"
                            method="POST">
                            @csrf
                            <input type="hidden" name="status" value="want_to_read">
                            <button type="submit"
                                class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                                Add to Library
                            </button>
                        </form>
                        <form
                            action="{{ route('books.add-to-library', ['openLibraryId' => urlencode($book->openlibrary_id)]) }}"
                            method="POST">
                            @csrf
                            <input type="hidden" name="status" value="favorite">
                            <button type="submit"
                                class="px-6 py-2 bg-red-500 text-white font-semibold rounded-lg hover:bg-red-600 transition">
                                Favorite
                            </button>
                        </form>
                    </div>
                    @if ($userLibrary)
                        <p class="text-green-600 mt-2">This book is in your library (Status:
                            {{ ucfirst(str_replace('_', ' ', $userLibrary->status)) }})</p>
                    @endif
                @endauth
            </div>
        </div>
    </div>
@endsection
