<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            // SQLite doesn't support changing enum directly, but we can change the column type to string
            // or use a raw query if it were a real enum. In Laravel/SQLite it's just a varchar.
            $table->string('status')->default('menunggu')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->string('status')->default('dipinjam')->change();
        });
    }
};
