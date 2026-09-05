<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
|------------------------------------------------------------------------------
| Tabel `activity_documentations` + `documentation_photos`
|------------------------------------------------------------------------------
| Dokumentasi kegiatan FSI (foto-foto kegiatan) — tampil di tab "Dokumentasi"
| halaman /info, detail per slug di /info/dokumentasi/{slug}.
| Foto disimpan di disk 'public' (documentations/...). Relasi 1-ke-banyak:
| 1 kegiatan → banyak foto; hapus kegiatan = fotonya ikut terhapus (cascade).
*/
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('activity_documentations')) {
            Schema::create('activity_documentations', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->date('event_date')->nullable()->index();
                $table->string('category')->nullable(); // mis. Kaderisasi, Praktikum Lab PAI, Komunitas
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('documentation_photos')) {
            Schema::create('documentation_photos', function (Blueprint $table) {
                $table->id();
                $table->foreignId('activity_documentation_id')
                    ->constrained('activity_documentations')
                    ->cascadeOnDelete();
                $table->string('image_path'); // path di disk 'public'
                $table->string('caption')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('documentation_photos');
        Schema::dropIfExists('activity_documentations');
    }
};
