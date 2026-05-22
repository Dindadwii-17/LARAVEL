<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        Book::create([
            'category_id' => 1,
            'title' => 'Laskar Pelangi',
            'author' => 'Andrea Hirata',
            'isbn' => '9789793062791',
            'quantity' => 5,
            'description' => 'Buku yang menceritakan tentang semangat anak-anak Belitong.'
        ]);

        Book::create([
            'category_id' => 3,
            'title' => 'Laravel for Beginners',
            'author' => 'John Doe',
            'isbn' => '123456789',
            'quantity' => 3,
            'description' => 'Tutorial dasar pengembangan web dengan Laravel.'
        ]);
    }
}
