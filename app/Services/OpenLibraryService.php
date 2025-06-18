<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class OpenLibraryService
{
    private $client;
    private $baseUrl = 'https://openlibrary.org';

    public function __construct()
    {
        $this->client = new Client([
            'timeout' => 30,
            'headers' => [
                'User-Agent' => 'OnlineBookSystem/1.0'
            ]
        ]);
    }

    public function searchBooks($query, $limit = 20, $offset = 0)
    {
        try {
            $response = $this->client->get("{$this->baseUrl}/search.json", [
                'query' => [
                    'q' => $query,
                    'limit' => $limit,
                    'offset' => $offset,
                    'fields' => 'key,title,author_name,cover_i,first_publish_year,subject,isbn,number_of_pages_median'
                ]
            ]);

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            Log::error('OpenLibrary API Error: ' . $e->getMessage());
            return ['docs' => [], 'numFound' => 0];
        }
    }

    public function getBookDetails($bookKey)
    {
        try {
            $response = $this->client->get("{$this->baseUrl}{$bookKey}.json");
            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            Log::error('OpenLibrary API Error: ' . $e->getMessage());
            return null;
        }
    }

    public function getBooksBySubject($subject, $limit = 20, $offset = 0)
    {
        try {
            $response = $this->client->get("{$this->baseUrl}/subjects/{$subject}.json", [
                'query' => [
                    'limit' => $limit,
                    'offset' => $offset
                ]
            ]);

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            Log::error('OpenLibrary API Error: ' . $e->getMessage());
            return ['works' => []];
        }
    }

    public function getBookText($bookKey)
    {
        try {
            // The Internet Archive identifier is often the same as the Open Library ID (e.g., OL...M)
            // You may need to adjust this based on the format of your `openlibrary_id`.
            // This example attempts to get the plain text version of the book.

            // It is a good practice to use the Guzzle client you've already configured.
            $response = $this->client->get("https://archive.org/stream/{$bookKey}/{$bookKey}_djvu.txt");
            return $response->getBody()->getContents();
        } catch (RequestException $e) {
            // Log the error and return null if the book content cannot be fetched.
            Log::error('Internet Archive API Error for book key ' . $bookKey . ': ' . $e->getMessage());
            return null;
        }
    }
}
