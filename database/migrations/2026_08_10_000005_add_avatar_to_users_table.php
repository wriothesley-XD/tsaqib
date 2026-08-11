<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom `avatar` ke tabel users.
     *
     * Menyimpan path avatar BAWAAN (preset) yang dipilih user di halaman
     * register / edit profil — mis. 'assets/images/avatar/guy.webp'.
     *
     * Sengaja dipisah dari `profile_photo_path` (yang menyimpan foto yang
     * di-UPLOAD ke disk 'public') agar dua jenis avatar tidak tertukar di
     * User::getAvatar(): upload tetap di storage/..., preset di asset statis.
     *
     * Idempoten via Schema::hasColumn(...), mengikuti pola migration
     * add_bio_and_avatar_to_users_table.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('profile_photo_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'avatar')) {
                $table->dropColumn('avatar');
            }
        });
    }
};
