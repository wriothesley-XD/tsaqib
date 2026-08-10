<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel votes: melacak satu suara per user per post.
     * Mendukung toggle (klik lagi untuk batal) & switch (dari up ke down / sebaliknya).
     * unique(user_id, post_id) mencegah duplikat suara.
     */
    public function up(): void
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // 'up' | 'down' (divalidasi di controller)
            $table->timestamps();

            $table->unique(['user_id', 'post_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('votes');
    }
};
