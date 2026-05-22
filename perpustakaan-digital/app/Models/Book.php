<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Book extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'author',
        'publisher',
        'publication_year',
        'isbn',
        'description',
        'stock',
        'cover_image',
        'category_id',
        'file_path',
        'file_size',
        'is_ebook',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Boot function to automatically generate slug from title.
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($book) {
            $book->slug = Str::slug($book->title) . '-' . Str::random(5);
        });
    }
}
