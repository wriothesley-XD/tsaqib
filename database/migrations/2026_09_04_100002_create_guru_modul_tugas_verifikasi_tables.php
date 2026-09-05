<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
|------------------------------------------------------------------------------
| Tabel RBAC + modul pembelajaran + tugas + verifikasi siswa.
|------------------------------------------------------------------------------
| Semua bert FK user_id → users (cascade on delete). Kolom "enum" dibuat
| sebagai string + validasi di lapisan app (FormRequest/validate) — SQLite
| tidak punya enum native dan constraint enum menyulitkan perubahan nilai.
|
|   guru_profiles.role_mapel: kategori modul di kolom `kategori` moduls
|   (string; daftar nilai mengikuti kebutuhan Laboratorium PAI).
|
| student_verifications.status: pending | approved | rejected (default pending).
| Pintu A (NISN whitelist) hanya menandai user terverifikasi tanpa upload;
| Pintu B (KTS) masuk sini sebagai baris status pending untuk approval admin.
*/
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('guru_profiles')) {
            Schema::create('guru_profiles', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
                $table->string('nip')->nullable()->index();
                $table->string('mapel_pengampu')->nullable();
                $table->json('kelas_diampu')->nullable(); // ["X","XI","XII"]
                $table->string('foto_path')->nullable();
                $table->string('wa_number')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('moduls')) {
            Schema::create('moduls', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // guru pengunggah
                $table->string('judul');
                $table->string('kategori')->nullable(); // enum di level app
                $table->string('target_kelas')->nullable(); // X | XI | XII
                $table->string('file_path'); // PDF modul, disk 'public'
                $table->text('deskripsi')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('tugas')) {
            Schema::create('tugas', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // guru pembuat
                $table->string('judul_tugas');
                $table->text('deskripsi')->nullable();
                $table->dateTime('deadline')->nullable()->index();
                $table->string('target_kelas')->nullable(); // X | XI | XII
                $table->string('link_google_classroom')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('student_verifications')) {
            Schema::create('student_verifications', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('nisn', 20)->index();
                $table->string('kelas')->nullable();
                $table->string('kts_photo_path')->nullable(); // Pintu B: foto kartu tanda siswa
                $table->string('status')->default('pending'); // pending | approved | rejected
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('student_verifications');
        Schema::dropIfExists('tugas');
        Schema::dropIfExists('moduls');
        Schema::dropIfExists('guru_profiles');
    }
};
