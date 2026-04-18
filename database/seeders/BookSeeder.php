<?php
namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $books = [
            [
                'isbn' => '9780141439518',
                'title' => 'Pride and Prejudice',
                'author' => 'Jane Austen',
                'price' => 12.99,
                'stock' => 15,
                'synopsis' => 'A classic novel of love and social standing in 19th century England.',
                'condition' => 'good',
                'image' => 'books/y995gd8yAZa3ztn3WwXK1puBobIepZNPxL45K6Mm.png',
            ],
            [
                'isbn' => '9780061120084',
                'title' => 'To Kill a Mockingbird',
                'author' => 'Harper Lee',
                'price' => 14.99,
                'stock' => 8,
                'synopsis' => 'A gripping, heart-wrenching tale of racial injustice in the Deep South.',
                'condition' => 'like-new',
                'image' => 'books/cktsh95dgPuZVU1lcBM2gsLb2ZV2BZcsXlqCwKvr.png',
            ],
            [
                'isbn' => '9780451524935',
                'title' => '1984',
                'author' => 'George Orwell',
                'price' => 11.99,
                'stock' => 3,
                'synopsis' => 'A dystopian social science fiction novel and cautionary tale.',
                'condition' => 'acceptable',
                'image' => 'books/t7dVX72CYr12WUo55pQYud4SPRouDZt1pn4d0xU3.png',
            ],
            [
                'isbn' => '9780743273565',
                'title' => 'The Great Gatsby',
                'author' => 'F. Scott Fitzgerald',
                'price' => 13.99,
                'stock' => 10,
                'synopsis' => 'A critique of the American Dream set in the Jazz Age.',
                'condition' => 'new',
                'image' => 'books/758VvCuMFIdW9Jp6cC3Unw9zaL1gKSkqy49OJo2y.png',
            ],
        ];

        foreach ($books as $book) {
            Book::updateOrCreate(
                ['isbn' => $book['isbn']],
                $book
            );
        }

        $this->command->info('✅ Books seeded successfully!');
    }
}