<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->string('id')->primary(); // e.g. T-101
            $table->string('member_id');
            $table->string('book_id');
            $table->date('borrow_date');
            $table->date('due_date');
            $table->date('return_date')->nullable();
            $table->string('status')->default('Dipinjam');
            $table->integer('fine')->default(0);
            $table->boolean('fine_paid')->default(false);
            $table->string('payment_proof')->nullable();
            $table->string('payment_status')->default('none'); // none, pending, approved, rejected
            $table->timestamps();

            // Foreign keys
            $table->foreign('member_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
