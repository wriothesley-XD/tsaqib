<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
|------------------------------------------------------------------------------
| Tabel `news` — berita/kabar terbaru TSAQIB.
|------------------------------------------------------------------------------
| Ditampilkan publik di /berita + /berita/{slug} dan dipreview di Beranda
| (section "Kabar Terbaru"). Kolom published_at menentukan visibilitas:
| NULL atau > now() = draf (tidak tampil ke publik). thumbnail disimpan di
| disk 'public' (news/...). Mengikuti pola migration idempoten proyek.
*/
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('news')) {
            Schema::create('news', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('slug')->unique();
                $table->string('excerpt')->nullable();
                $table->longText('content');
                $table->string('thumbnail')->nullable();
                $table->timestamp('published_at')->nullable()->index();
                $table->foreignId('user_id')
                    ->nullable()
                    ->constrained()
                    ->nullOnDelete();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
