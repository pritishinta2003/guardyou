<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('gender')->nullable()->change();
        });

        // Hapus constraint lama kalau ada
        DB::statement("ALTER TABLE users DROP CONSTRAINT IF EXISTS users_gender_check");

        // Tambahkan constraint baru
        DB::statement("ALTER TABLE users ADD CONSTRAINT users_gender_check CHECK (gender IN ('P', 'W'))");
    }

    public function down(): void
    {
        // Hapus constraint saat rollback
        DB::statement("ALTER TABLE users DROP CONSTRAINT IF EXISTS users_gender_check");

        Schema::table('users', function (Blueprint $table) {
            $table->string('gender')->nullable()->change();
        });
    }
};