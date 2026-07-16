<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Book;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Support\Facades\Hash;

class LiteraSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Categories
        $categories = ['Novel', 'Fiksi', 'Self Improvement', 'Sejarah', 'Teknologi', 'Sains', 'Biografi'];
        foreach ($categories as $catName) {
            Category::create(['name' => $catName]);
        }

        // 2. Seed Admin
        User::create([
            'id' => 'A-001',
            'name' => 'Admin Utama',
            'email' => 'administrator@litera.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'Aktif'
        ]);

        // 3. Seed Members
        $members = [
            [
                'id' => 'M-001',
                'name' => 'Ahmad Fauzi',
                'email' => 'ahmad.fauzi@email.com',
                'created_at' => '2025-01-15 00:00:00',
                'status' => 'Aktif'
            ],
            [
                'id' => 'M-002',
                'name' => 'Siti Rahmawati',
                'email' => 'siti.rahma@email.com',
                'created_at' => '2025-03-22 00:00:00',
                'status' => 'Aktif'
            ],
            [
                'id' => 'M-003',
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@email.com',
                'created_at' => '2025-05-10 00:00:00',
                'status' => 'Ditangguhkan'
            ],
            [
                'id' => 'M-004',
                'name' => 'Dewi Lestari',
                'email' => 'dewi.lestari@email.com',
                'created_at' => '2025-06-02 00:00:00',
                'status' => 'Aktif'
            ],
            [
                'id' => 'M-005',
                'name' => 'Rian Hidayat',
                'email' => 'rian.hidayat@email.com',
                'created_at' => '2025-09-18 00:00:00',
                'status' => 'Aktif'
            ]
        ];

        foreach ($members as $index => $m) {
            User::create([
                'id' => $m['id'],
                'name' => $m['name'],
                'email' => $m['email'],
                'password' => Hash::make('password'),
                'role' => 'member',
                'status' => $m['status'],
                'created_at' => $m['created_at'],
                'nim' => '10923064' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'phone' => '0812' . rand(10000000, 99999999),
                'address' => 'Jl. Merdeka No. ' . ($index + 1) . ', Bandung',
                'gender' => ($index % 2 === 0) ? 'Laki-laki' : 'Perempuan',
                'is_approved' => true,
                'membership_status' => ($index < 2) ? 'premium' : 'regular',
                'premium_until' => ($index < 2) ? now()->addMonths(12) : null
            ]);
        }

        // 4. Seed Books
        $books = [
            [
                'id' => 'B-001',
                'title' => 'Laskar Pelangi',
                'author' => 'Andrea Hirata',
                'category' => 'Novel',
                'isbn' => '978-979-3062-79-1',
                'stock' => 5,
                'available' => 3,
                'cover' => '🎨',
                'file_size' => '4.2 MB',
                'pages' => 529,
                'pdf_url' => 'https://www.adobe.com/be_en/active-use/pdf/Alice_in_Wonderland.pdf'
            ],
            [
                'id' => 'B-002',
                'title' => 'Bumi',
                'author' => 'Tere Liye',
                'category' => 'Fiksi',
                'isbn' => '978-602-03-3295-6',
                'stock' => 8,
                'available' => 8,
                'cover' => '🌍',
                'file_size' => '3.8 MB',
                'pages' => 440,
                'pdf_url' => 'https://www.gutenberg.org/files/1342/1342-pdf.pdf'
            ],
            [
                'id' => 'B-003',
                'title' => 'Filosofi Teras',
                'author' => 'Henry Manampiring',
                'category' => 'Self Improvement',
                'isbn' => '978-602-412-518-9',
                'stock' => 4,
                'available' => 2,
                'cover' => '🏛️',
                'file_size' => '5.1 MB',
                'pages' => 320,
                'pdf_url' => 'https://www.gutenberg.org/files/5200/5200-pdf.pdf'
            ],
            [
                'id' => 'B-004',
                'title' => 'Atomic Habits',
                'author' => 'James Clear',
                'category' => 'Self Improvement',
                'isbn' => '978-1-84-794183-1',
                'stock' => 10,
                'available' => 9,
                'cover' => '⚛️',
                'file_size' => '2.5 MB',
                'pages' => 280,
                'pdf_url' => 'https://www.gutenberg.org/files/1661/1661-pdf.pdf'
            ],
            [
                'id' => 'B-005',
                'title' => 'Sapiens',
                'author' => 'Yuval Noah Harari',
                'category' => 'Sejarah',
                'isbn' => '978-602-440-354-6',
                'stock' => 3,
                'available' => 1,
                'cover' => '🐒',
                'file_size' => '8.4 MB',
                'pages' => 512,
                'pdf_url' => 'https://raw.githubusercontent.com/manjunath5496/classic-ebooks/master/Oliver%20Twist%20-%20Charles%20Dickens.pdf'
            ],
            [
                'id' => 'B-006',
                'title' => 'Pengantar Teknologi Informasi',
                'author' => 'Prof. Richardus',
                'category' => 'Teknologi',
                'isbn' => '978-979-29-2156-4',
                'stock' => 6,
                'available' => 6,
                'cover' => '💻',
                'file_size' => '6.7 MB',
                'pages' => 410,
                'pdf_url' => 'https://git-scm.com/book/en/v2/download/pdf'
            ]
        ];

        foreach ($books as $index => $b) {
            $b['is_ebook'] = ($index >= 3); // B-004, B-005, B-006 are E-Books
            $b['slug'] = \Illuminate\Support\Str::slug($b['title']);
            $b['file_path'] = 'ebooks/' . $b['slug'] . '.pdf';
            Book::create($b);
        }

        // 5. Seed Transactions
        $transactions = [
            [
                'id' => 'T-101',
                'member_id' => 'M-001',
                'book_id' => 'B-003',
                'borrow_date' => '2026-07-01',
                'due_date' => '2026-07-08',
                'return_date' => null,
                'status' => 'Terlambat',
                'fine' => 8000,
                'fine_paid' => false
            ],
            [
                'id' => 'T-102',
                'member_id' => 'M-002',
                'book_id' => 'B-005',
                'borrow_date' => '2026-07-05',
                'due_date' => '2026-07-12',
                'return_date' => null,
                'status' => 'Dipinjam',
                'fine' => 0,
                'fine_paid' => false
            ],
            [
                'id' => 'T-103',
                'member_id' => 'M-005',
                'book_id' => 'B-001',
                'borrow_date' => '2026-07-10',
                'due_date' => '2026-07-17',
                'return_date' => null,
                'status' => 'Dipinjam',
                'fine' => 0,
                'fine_paid' => false
            ],
            [
                'id' => 'T-104',
                'member_id' => 'M-004',
                'book_id' => 'B-004',
                'borrow_date' => '2026-06-25',
                'due_date' => '2026-07-02',
                'return_date' => '2026-07-01',
                'status' => 'Kembali',
                'fine' => 0,
                'fine_paid' => false
            ]
        ];

        foreach ($transactions as $t) {
            Transaction::create($t);
        }
    }
}
