<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_books_can_be_created_listed_shown_updated_and_deleted(): void
    {
        $bookData = [
            'title' => 'Laravel untuk Pemula',
            'description' => 'Panduan membangun aplikasi Laravel.',
            'status' => 1,
        ];

        $this->postJson('/api/books', $bookData)
            ->assertCreated()
            ->assertJsonPath('title', $bookData['title'])
            ->assertJsonPath('description', $bookData['description'])
            ->assertJsonPath('status', $bookData['status']);

        $book = Book::query()->firstOrFail();

        $this->getJson('/api/books')
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonPath('0.id', $book->id);

        $this->getJson("/api/books/{$book->id}")
            ->assertOk()
            ->assertJsonPath('title', $bookData['title']);

        $this->patchJson("/api/books/{$book->id}", ['status' => 2])
            ->assertOk()
            ->assertJsonPath('status', 2)
            ->assertJsonPath('title', $bookData['title']);

        $this->deleteJson("/api/books/{$book->id}")
            ->assertOk()
            ->assertJsonPath('message', 'Book deleted');

        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }

    public function test_creating_a_book_requires_valid_fields(): void
    {
        $this->postJson('/api/books', [
            'title' => '',
            'description' => 'Deskripsi',
            'status' => -1,
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['title', 'status']);

        $this->assertDatabaseCount('books', 0);
    }

    public function test_missing_books_return_a_json_404_response(): void
    {
        $this->getJson('/api/books/999')
            ->assertNotFound()
            ->assertJsonPath('message', 'Book not found');

        $this->putJson('/api/books/999', [
            'title' => 'Buku',
            'description' => 'Deskripsi',
            'status' => 1,
        ])
            ->assertNotFound()
            ->assertJsonPath('message', 'Book not found');

        $this->deleteJson('/api/books/999')
            ->assertNotFound()
            ->assertJsonPath('message', 'Book not found');
    }
}
