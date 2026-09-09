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
        Schema::table('guru_profiles', function (Blueprint $table) {
            if (! Schema::hasColumn('guru_profiles', 'nama')) {
                $table->string('nama')->nullable()->after('user_id');
            }
            if (! Schema::hasColumn('guru_profiles', 'email')) {
                $table->string('email')->nullable()->after('nama');
            }
            if (! Schema::hasColumn('guru_profiles', 'deskripsi')) {
                $table->text('deskripsi')->nullable()->after('email');
            }
            if (! Schema::hasColumn('guru_profiles', 'facebook_url')) {
                $table->string('facebook_url')->nullable()->after('deskripsi');
            }
            if (! Schema::hasColumn('guru_profiles', 'instagram_url')) {
                $table->string('instagram_url')->nullable()->after('facebook_url');
            }
        });

        // Buat user_id nullable agar admin dapat menambahkan guru pengampu
        // tanpa harus membuat akun login pengguna terlebih dahulu.
        Schema::table('guru_profiles', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guru_profiles', function (Blueprint $table) {
            if (Schema::hasColumn('guru_profiles', 'instagram_url')) {
                $table->dropColumn('instagram_url');
            }
            if (Schema::hasColumn('guru_profiles', 'facebook_url')) {
                $table->dropColumn('facebook_url');
            }
            if (Schema::hasColumn('guru_profiles', 'deskripsi')) {
                $table->dropColumn('deskripsi');
            }
            if (Schema::hasColumn('guru_profiles', 'email')) {
                $table->dropColumn('email');
            }
            if (Schema::hasColumn('guru_profiles', 'nama')) {
                $table->dropColumn('nama');
            }
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }
};
