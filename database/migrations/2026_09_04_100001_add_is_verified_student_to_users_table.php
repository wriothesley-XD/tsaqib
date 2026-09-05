<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
|------------------------------------------------------------------------------
| users: kolom verifikasi siswa.
|------------------------------------------------------------------------------
| CATATAN: kolom `role` SUDAH ADA (migration 2026_07_31_..., string, default
| 'member') dan dipakai middleware admin sekarang. Nilai rencana blueprint
| admin/guru/siswa/umum memakai kolom ini tanpa membuat ulang — 'member'
| existing diperlakukan setara 'umum' (bukan admin/guru/siswa). Konversi
| nilai lama → baru ditangani di langkah RBAC, bukan di sini.
*/
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'is_verified_student')) {
                $table->boolean('is_verified_student')->default(false)->after('role');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'is_verified_student')) {
                $table->dropColumn('is_verified_student');
            }
        });
    }
};
