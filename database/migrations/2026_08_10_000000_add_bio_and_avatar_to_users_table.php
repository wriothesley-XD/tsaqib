<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom `bio` + `profile_photo_path` ke tabel users.
     *
     * - `profile_photo_path` sudah dirujuk di User::getAvatar() tapi kolomnya
     *   belum pernah dibuat di migration manapun, jadi upload avatar tak bisa
     *   tersimpan permanen sebelumnya.
     * - `bio` dipakai di halaman profil (modal Edit Profil).
     *
     * Pakai guard Schema::hasColumn(...) agar idempoten & aman dijalankan
     * ulang, mengikuti pola migration add_columns_to_comments_table.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'bio')) {
                $table->string('bio', 160)->nullable();
            }
            if (! Schema::hasColumn('users', 'profile_photo_path')) {
                $table->string('profile_photo_path')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'profile_photo_path')) {
                $table->dropColumn('profile_photo_path');
            }
            if (Schema::hasColumn('users', 'bio')) {
                $table->dropColumn('bio');
            }
        });
    }
};
