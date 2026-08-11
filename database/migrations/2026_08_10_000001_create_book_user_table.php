<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
|------------------------------------------------------------------------------
| Pivot buku <-> user untuk "Koleksi" & "Tersimpan" di Perpustakaan Digital.
|------------------------------------------------------------------------------
| Satu tabel menampung dua daftar pribadi user, dibedakan lewat kolom `type`:
|   - 'collection' => My Collection (koleksi pribadi yang dikurasi user)
|   - 'saved'      => Saved (bookmark buku untuk dibaca nanti)
| Unique pada (user_id, book_id, type) memungkinkan satu buku berada di kedua
| daftar sekaligus tanpa duplikat pada daftar yang sama.
*/
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('book_user', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('book_id')->constrained()->cascadeOnDelete();
            $table->string('type')->default('saved'); // 'collection' | 'saved'
            $table->timestamps();

            $table->unique(['user_id', 'book_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('book_user');
    }
};
