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
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('test_id')->nullable()->constrained()->onDelete('set null');
            $table->string('test_name_snapshot')->nullable(); // Simpan nama tes di sini
            $table->string('test_level_snapshot')->nullable(); // Tambahkan ini
            $table->integer('test_class_snapshot')->nullable(); // Tambahkan ini
            // Kita simpan jawaban dalam format JSON agar simpel
            $table->json('answers'); 
            $table->integer('score')->nullable(); // Tetap ada buat nanti kalau guru sudah kasih nilai
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
