<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan kategori sudah ada sebelum menjalankan seeder ini
        $fiksi = Category::where('name', 'Fiksi')->first();
        $nonFiksi = Category::where('name', 'Non-Fiksi')->first();
        $sains = Category::where('name', 'Sains')->first();
        $teknologi = Category::where('name', 'Teknologi')->first();
        $sejarah = Category::where('name', 'Sejarah')->first();

        $books = [
            [
                'title' => 'Filosofi Teras',
                'author' => 'Henry Manampiring',
                'publisher' => 'Buku Kompas',
                'publication_year' => 2018,
                'isbn' => '978-602-412-518-9',
                'description' => 'Buku yang memperkenalkan filsafat Stoikisme dengan cara yang praktis dan relevan untuk kehidupan modern di Indonesia.',
                'stock' => 12,
                'category_id' => $nonFiksi?->id,
            ],
            [
                'title' => 'Sapiens: Riwayat Singkat Umat Manusia',
                'author' => 'Yuval Noah Harari',
                'publisher' => 'Kepustakaan Populer Gramedia',
                'publication_year' => 2011,
                'isbn' => '978-602-424-828-4',
                'description' => 'Menjelajahi sejarah umat manusia dari Zaman Batu hingga abad ke-21, membahas bagaimana biologi dan sejarah mendefinisikan kita.',
                'stock' => 10,
                'category_id' => $sejarah?->id,
            ],
            [
                'title' => 'The Pragmatic Programmer',
                'author' => 'Andrew Hunt & David Thomas',
                'publisher' => 'Addison-Wesley',
                'publication_year' => 1999,
                'isbn' => '978-020-161-622-4',
                'description' => 'Salah satu buku pemrograman paling berpengaruh yang membahas tips dan praktik terbaik dalam pengembangan perangkat lunak.',
                'stock' => 5,
                'category_id' => $teknologi?->id,
            ],
            [
                'title' => 'Cantik Itu Luka',
                'author' => 'Eka Kurniawan',
                'publisher' => 'Gramedia Pustaka Utama',
                'publication_year' => 2002,
                'isbn' => '978-602-031-258-3',
                'description' => 'Sebuah novel epik yang menggabungkan sejarah, roman, dan realisme magis tentang sejarah kelam Indonesia.',
                'stock' => 7,
                'category_id' => $fiksi?->id,
            ],
            [
                'title' => 'Astrophysics for People in a Hurry',
                'author' => 'Neil deGrasse Tyson',
                'publisher' => 'W. W. Norton & Company',
                'publication_year' => 2017,
                'isbn' => '978-039-360-939-4',
                'description' => 'Penjelasan singkat namun padat tentang dasar-dasar alam semesta bagi mereka yang memiliki waktu terbatas.',
                'stock' => 15,
                'category_id' => $sains?->id,
            ],
            [
                'title' => 'Laskar Pelangi',
                'author' => 'Andrea Hirata',
                'publisher' => 'Bentang Pustaka',
                'publication_year' => 2005,
                'isbn' => '978-979-306-279-2',
                'description' => 'Kisah perjuangan 10 anak di Belitung dalam mengejar pendidikan di sekolah yang penuh keterbatasan.',
                'stock' => 20,
                'category_id' => $fiksi?->id,
            ],
            [
                'title' => 'Atomic Habits',
                'author' => 'James Clear',
                'publisher' => 'Gramedia Pustaka Utama',
                'publication_year' => 2018,
                'isbn' => '978-602-063-317-6',
                'description' => 'Panduan komprehensif untuk membangun kebiasaan baik dan menghilangkan kebiasaan buruk dengan langkah kecil.',
                'stock' => 25,
                'category_id' => $nonFiksi?->id,
            ],
            [
                'title' => 'Clean Code',
                'author' => 'Robert C. Martin',
                'publisher' => 'Prentice Hall',
                'publication_year' => 2008,
                'isbn' => '978-013-235-088-4',
                'description' => 'Panduan praktis untuk menulis kode yang bersih, mudah dibaca, dan mudah dipelihara.',
                'stock' => 8,
                'category_id' => $teknologi?->id,
            ],
            [
                'title' => 'Bumi Manusia',
                'author' => 'Pramoedya Ananta Toer',
                'publisher' => 'Lentera Dipantara',
                'publication_year' => 1980,
                'isbn' => '978-979-973-123-5',
                'description' => 'Roman sejarah yang mengisahkan perjuangan pemuda pribumi di masa penjajahan Belanda.',
                'stock' => 15,
                'category_id' => $fiksi?->id,
            ],
            [
                'title' => 'A Brief History of Time',
                'author' => 'Stephen Hawking',
                'publisher' => 'Bantam Books',
                'publication_year' => 1988,
                'isbn' => '978-055-338-016-3',
                'description' => 'Menjelaskan konsep-konsep kompleks kosmologi mulai dari Big Bang hingga lubang hitam.',
                'stock' => 10,
                'category_id' => $sains?->id,
            ],
            [
                'title' => 'The Lean Startup',
                'author' => 'Eric Ries',
                'publisher' => 'Crown Business',
                'publication_year' => 2011,
                'isbn' => '978-030-788-789-4',
                'description' => 'Pendekatan baru dalam membangun bisnis rintisan melalui inovasi berkelanjutan.',
                'stock' => 12,
                'category_id' => $teknologi?->id,
            ],
            [
                'title' => 'Home Deus: Masa Depan Umat Manusia',
                'author' => 'Yuval Noah Harari',
                'publisher' => 'Gramedia Pustaka Utama',
                'publication_year' => 2015,
                'isbn' => '978-602-038-057-5',
                'description' => 'Menjelajahi masa depan umat manusia dengan kemajuan teknologi dan kecerdasan buatan.',
                'stock' => 9,
                'category_id' => $sejarah?->id,
            ],
            [
                'title' => 'Thinking, Fast and Slow',
                'author' => 'Daniel Kahneman',
                'publisher' => 'Farrar, Straus and Giroux',
                'publication_year' => 2011,
                'isbn' => '978-037-427-563-1',
                'description' => 'Menjelaskan dua sistem yang menggerakkan cara kita berpikir: sistem cepat dan lambat.',
                'stock' => 14,
                'category_id' => $nonFiksi?->id,
            ],
            [
                'title' => 'Guns, Germs, and Steel',
                'author' => 'Jared Diamond',
                'publisher' => 'W. W. Norton & Company',
                'publication_year' => 1997,
                'isbn' => '978-039-331-755-8',
                'description' => 'Analisis sejarah manusia dan mengapa peradaban tertentu lebih maju dari yang lain.',
                'stock' => 7,
                'category_id' => $sejarah?->id,
            ],
            [
                'title' => 'Educated',
                'author' => 'Tara Westover',
                'publisher' => 'Random House',
                'publication_year' => 2018,
                'isbn' => '978-039-959-050-4',
                'description' => 'Memoar tentang perjuangan seorang wanita keluar dari keluarga konservatif untuk menempuh pendidikan.',
                'stock' => 11,
                'category_id' => $nonFiksi?->id,
            ],
            [
                'title' => 'Deep Work',
                'author' => 'Cal Newport',
                'publisher' => 'Grand Central Publishing',
                'publication_year' => 2016,
                'isbn' => '978-145-558-669-1',
                'description' => 'Strategi untuk tetap fokus di dunia yang penuh gangguan untuk mencapai produktivitas maksimal.',
                'stock' => 16,
                'category_id' => $nonFiksi?->id,
            ],
            [
                'title' => 'The Selfish Gene',
                'author' => 'Richard Dawkins',
                'publisher' => 'Oxford University Press',
                'publication_year' => 1976,
                'isbn' => '978-019-929-115-1',
                'description' => 'Membahas evolusi dari perspektif gen dan bagaimana perilaku mahluk hidup dipengaruhi gen.',
                'stock' => 6,
                'category_id' => $sains?->id,
            ],
            [
                'title' => 'Refactoring',
                'author' => 'Martin Fowler',
                'publisher' => 'Addison-Wesley Professional',
                'publication_year' => 1999,
                'isbn' => '978-020-148-567-7',
                'description' => 'Buku standar tentang teknik memperbaiki desain kode yang sudah ada tanpa mengubah perilakunya.',
                'stock' => 4,
                'category_id' => $teknologi?->id,
            ],
            [
                'title' => 'PULANG',
                'author' => 'Leila S. Chudori',
                'publisher' => 'Kepustakaan Populer Gramedia',
                'publication_year' => 2012,
                'isbn' => '978-979-910-515-8',
                'description' => 'Novel tentang eksil Indonesia di Paris dan dampaknya terhadap keluarga mereka.',
                'stock' => 13,
                'category_id' => $fiksi?->id,
            ],
            [
                'title' => 'Cosmos',
                'author' => 'Carl Sagan',
                'publisher' => 'Random House',
                'publication_year' => 1980,
                'isbn' => '978-039-450-294-6',
                'description' => 'Penjelajahan alam semesta, sains, dan peradaban manusia yang sangat inspiratif.',
                'stock' => 18,
                'category_id' => $sains?->id,
            ],
            [
                'title' => 'The Phoenix Project',
                'author' => 'Gene Kim',
                'publisher' => 'IT Revolution Press',
                'publication_year' => 2013,
                'isbn' => '978-098-826-259-1',
                'description' => 'Novel tentang DevOps dan bagaimana IT membantu bisnis menjadi lebih kompetitif.',
                'stock' => 10,
                'category_id' => $teknologi?->id,
            ],
            [
                'title' => 'Melihat Indonesia dari Sepeda',
                'author' => 'Haryo Damardono',
                'publisher' => 'Buku Kompas',
                'publication_year' => 2015,
                'isbn' => '978-979-709-968-8',
                'description' => 'Catatan perjalanan bersepeda keliling nusantara yang mengungkap sisi lain keindahan Indonesia.',
                'stock' => 8,
                'category_id' => $nonFiksi?->id,
            ],
            [
                'title' => 'World Order',
                'author' => 'Henry Kissinger',
                'publisher' => 'Penguin Books',
                'publication_year' => 2014,
                'isbn' => '978-159-420-614-6',
                'description' => 'Analisis geopolitik tentang tatanan dunia dan tantangannya di masa kini.',
                'stock' => 6,
                'category_id' => $sejarah?->id,
            ],
            [
                'title' => 'Ready Player One',
                'author' => 'Ernest Cline',
                'publisher' => 'Crown Publishing',
                'publication_year' => 2011,
                'isbn' => '978-030-788-743-6',
                'description' => 'Petualangan fiksi ilmiah di dalam dunia virtual reality (OASIS).',
                'stock' => 22,
                'category_id' => $fiksi?->id,
            ],
            [
                'title' => 'Zero to One',
                'author' => 'Peter Thiel',
                'publisher' => 'Crown Business',
                'publication_year' => 2014,
                'isbn' => '978-080-413-929-8',
                'description' => 'Cara membangun masa depan melalui penciptaan sesuatu yang baru.',
                'stock' => 14,
                'category_id' => $teknologi?->id,
            ],
            [
                'title' => 'The Sixth Extinction',
                'author' => 'Elizabeth Kolbert',
                'publisher' => 'Henry Holt and Co.',
                'publication_year' => 2014,
                'isbn' => '978-080-509-299-8',
                'description' => 'Membahas krisis lingkungan global dan kepunahan massal yang sedang terjadi.',
                'stock' => 9,
                'category_id' => $sains?->id,
            ],
        ];

        foreach ($books as $bookData) {
            $isbn = $bookData['isbn'];
            $existingBook = Book::where('isbn', $isbn)->first();

            if ($existingBook) {
                // Update data yang sudah ada (kecuali slug agar link tidak berubah)
                $existingBook->update($bookData);
            } else {
                // Buat data baru jika belum ada
                $bookData['slug'] = Str::slug($bookData['title']) . '-' . Str::random(5);
                Book::create($bookData);
            }
        }
    }
}
