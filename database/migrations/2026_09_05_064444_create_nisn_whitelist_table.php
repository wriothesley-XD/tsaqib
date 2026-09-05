<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
|------------------------------------------------------------------------------
| Whitelist NISN siswa — Pintu A verifikasi siswa (menggantikan config/nisn.php).
|------------------------------------------------------------------------------
| Sebelumnya whitelist adalah array statis di config/nisn.php — tiap tambah
| NISN harus edit file & deploy ulang, dan tidak ada cara import massal.
| Sekarang whitelist adalah tabel biasa, dikelola dari Admin Panel:
|   - tambah manual satu-satu, atau
|   - import massal dari file CSV/Excel (lihat AdminController::importNisnWhitelist).
*/
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nisn_whitelist', function (Blueprint $table) {
            $table->id();
            $table->string('nisn', 20)->unique();
            $table->string('nama')->nullable();
            $table->string('kelas', 20)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nisn_whitelist');
    }
};
