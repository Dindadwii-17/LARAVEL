<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'member_id',
        'book_id',
        'borrow_date',
        'due_date',
        'return_date',
        'status',
        'fine',
        'fine_paid',
        'payment_proof',
        'payment_status',
    ];

    protected $casts = [
        'fine_paid' => 'boolean',
        'borrow_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
    ];

    public function member()
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }
}
