<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Seeder;

class AssignCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fiksi = Category::where('slug', 'fiksi')->first();

        Book::whereIn('title', [
            'Laskar Pelangi', 
            'Bumi Manusia', 
            'Negeri 5 Menara', 
            'Pulang', 
            'Dilan: Dia adalah Dilanku Tahun 1990'
        ])->update(['category_id' => $fiksi->id]);
    }
}
