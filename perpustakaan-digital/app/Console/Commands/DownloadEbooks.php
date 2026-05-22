<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Book;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class DownloadEbooks extends Command
{
    protected $signature = 'ebooks:download';
    protected $description = 'Download sample PDFs from the internet and seed the database';

    public function handle()
    {
        $books = [
            // Classic Literature (Category 1: Fiksi)
            ['title' => 'Alice in Wonderland', 'author' => 'Lewis Carroll', 'cat' => 1, 'url' => 'https://www.adobe.com/be_en/active-use/pdf/Alice_in_Wonderland.pdf'],
            ['title' => 'Pride and Prejudice', 'author' => 'Jane Austen', 'cat' => 1, 'url' => 'https://www.gutenberg.org/files/1342/1342-pdf.pdf'],
            ['title' => 'Metamorphosis', 'author' => 'Franz Kafka', 'cat' => 1, 'url' => 'https://www.gutenberg.org/files/5200/5200-pdf.pdf'],
            ['title' => 'Sherlock Holmes', 'author' => 'Arthur Conan Doyle', 'cat' => 1, 'url' => 'https://www.gutenberg.org/files/1661/1661-pdf.pdf'],
            ['title' => 'Oliver Twist', 'author' => 'Charles Dickens', 'cat' => 1, 'url' => 'https://raw.githubusercontent.com/manjunath5496/classic-ebooks/master/Oliver%20Twist%20-%20Charles%20Dickens.pdf'],
            
            // History/Philosophy (Category 5: Sejarah / 2: Non-Fiksi)
            ['title' => 'The Art of War', 'author' => 'Sun Tzu', 'cat' => 5, 'url' => 'https://www.gutenberg.org/files/132/132-pdf.pdf'],
            ['title' => 'Meditations', 'author' => 'Marcus Aurelius', 'cat' => 2, 'url' => 'https://raw.githubusercontent.com/manjunath5496/classic-ebooks/master/Meditations%20-%20Marcus%20Aurelius.pdf'],
            
            // Technology (Category 4: Teknologi)
            ['title' => 'Pro Git', 'author' => 'Scott Chacon', 'cat' => 4, 'url' => 'https://git-scm.com/book/en/v2/download/pdf'],
            ['title' => 'Eloquent JavaScript', 'author' => 'Marijn Haverbeke', 'cat' => 4, 'url' => 'https://eloquentjavascript.net/Eloquent_JavaScript.pdf'],
            ['title' => 'The Docker Handbook', 'author' => 'Farhan Hasin Chowdhury', 'cat' => 4, 'url' => 'https://www.freecodecamp.org/news/content/images/2021/04/The-Docker-Handbook.pdf'],
            
            // Adding more to reach 20
            ['title' => 'Treasure Island', 'author' => 'Robert Louis Stevenson', 'cat' => 1, 'url' => 'https://raw.githubusercontent.com/manjunath5496/classic-ebooks/master/Treasure%20Island%20-%20Robert%20Louis%20Stevenson.pdf'],
            ['title' => 'Dracula', 'author' => 'Bram Stoker', 'cat' => 1, 'url' => 'https://raw.githubusercontent.com/manjunath5496/classic-ebooks/master/Dracula%20-%20Bram%20Stoker.pdf'],
            ['title' => 'Frankenstein', 'author' => 'Mary Shelley', 'cat' => 1, 'url' => 'https://raw.githubusercontent.com/manjunath5496/classic-ebooks/master/Frankenstein%20-%20Mary%20Shelley.pdf'],
            ['title' => 'The Great Gatsby', 'author' => 'F. Scott Fitzgerald', 'cat' => 1, 'url' => 'https://raw.githubusercontent.com/manjunath5496/classic-ebooks/master/The%20Great%20Gatsby%20-%20F.%20Scott%20Fitzgerald.pdf'],
            ['title' => 'Jane Eyre', 'author' => 'Charlotte Bronte', 'cat' => 1, 'url' => 'https://raw.githubusercontent.com/manjunath5496/classic-ebooks/master/Jane%20Eyre%20-%20Charlotte%20Bronte.pdf'],
            ['title' => 'Wuthering Heights', 'author' => 'Emily Bronte', 'cat' => 1, 'url' => 'https://raw.githubusercontent.com/manjunath5496/classic-ebooks/master/Wuthering%20Heights%20-%20Emily%20Bronte.pdf'],
            ['title' => 'The Prophet', 'author' => 'Kahlil Gibran', 'cat' => 2, 'url' => 'https://raw.githubusercontent.com/manjunath5496/classic-ebooks/master/The%20Prophet%20-%20Kahlil%20Gibran.pdf'],
            ['title' => 'Siddhartha', 'author' => 'Hermann Hesse', 'cat' => 1, 'url' => 'https://raw.githubusercontent.com/manjunath5496/classic-ebooks/master/Siddhartha%20-%20Hermann%20Hesse.pdf'],
            ['title' => 'The Importance of Being Earnest', 'author' => 'Oscar Wilde', 'cat' => 1, 'url' => 'https://raw.githubusercontent.com/manjunath5496/classic-ebooks/master/The%20Importance%20of%20Being%20Earnest%20-%20Oscar%20Wilde.pdf'],
            ['title' => 'Moby Dick', 'author' => 'Herman Melville', 'cat' => 1, 'url' => 'https://raw.githubusercontent.com/manjunath5496/classic-ebooks/master/Moby%20Dick%20-%20Herman%20Melville.pdf'],
        ];

        $this->info('Starting ebook population (20 books)...');

        foreach ($books as $bookData) {
            $slug = Str::slug($bookData['title']);
            $fileName = 'ebooks/' . $slug . '.pdf';
            
            $this->info("Processing: {$bookData['title']}...");
            
            $success = false;
            $content = '';
            
            // Try real download first
            try {
                $response = Http::withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
                ])->timeout(10)->get($bookData['url']);
                
                if ($response->successful()) {
                    $content = $response->body();
                    $success = true;
                    $this->info("  - Download successful!");
                }
            } catch (\Exception $e) {
                $this->warn("  - Download failed: " . $e->getMessage());
            }
            
            // Fallback to placeholder if download failed OR file is too small (might be an error page)
            if (!$success || strlen($content) < 1000) {
                $this->info("  - Using placeholder for {$bookData['title']}");
                $content = "%PDF-1.4\n1 0 obj<</Type/Catalog/Pages 2 0 R>>endobj\n2 0 obj<</Type/Pages/Count 1/Kids[3 0 R]>>endobj\n3 0 obj<</Type/Page/Parent 2 0 R/MediaBox[0 0 612 792]/Contents 4 0 R>>endobj\n4 0 obj<</Length 51>>stream\nBT /F1 24 Tf 100 700 Td (" . $bookData['title'] . " by " . $bookData['author'] . ") Tj ET\nendstream\nendobj\nxref\n0 5\n0000000000 65535 f\n0000000018 00000 n\n0000000077 00000 n\n0000000135 00000 n\n0000000244 00000 n\ntrailer<</Size 5/Root 1 0 R>>\nstartxref\n345\n%%EOF";
            }
            
            Storage::disk('public')->put($fileName, $content);
            $size = $this->formatBytes(strlen($content));
            
            Book::updateOrCreate(
                ['title' => $bookData['title']],
                [
                    'author' => $bookData['author'],
                    'category_id' => $bookData['cat'],
                    'file_path' => $fileName,
                    'file_size' => $size,
                    'is_ebook' => true,
                    'stock' => 0,
                    'description' => "E-Book: {$bookData['title']} karya {$bookData['author']}. Nikmati bacaan digital berkualitas di perpustakaan kami.",
                ]
            );
            
            $this->info("  - Saved: {$fileName} ({$size})");
        }

        $this->info('Ebook seeding completed successfully.');
    }

    private function formatBytes($bytes, $precision = 2) {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
