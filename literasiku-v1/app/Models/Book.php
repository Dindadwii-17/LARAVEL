<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'title',
        'author',
        'category',
        'isbn',
        'stock',
        'available',
        'cover',
        'file_size',
        'pages',
        'pdf_url',
        'is_ebook',
        'slug',
        'file_path'
    ];

    protected $casts = [
        'is_ebook' => 'boolean',
    ];
}
