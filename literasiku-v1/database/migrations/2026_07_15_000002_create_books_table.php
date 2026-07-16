<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->string('id')->primary(); // e.g. B-001
            $table->string('title');
            $table->string('author');
            $table->string('category');
            $table->string('isbn');
            $table->integer('stock')->default(1);
            $table->integer('available')->default(1);
            $table->string('cover')->default('📚');
            $table->string('file_size')->default('2.0 MB');
            $table->integer('pages')->default(100);
            $table->text('pdf_url')->nullable();
            $table->boolean('is_ebook')->default(false);
            $table->string('slug')->nullable();
            $table->string('file_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
