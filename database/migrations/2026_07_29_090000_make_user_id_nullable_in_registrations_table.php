<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Open Recruitment sekarang publik — orang bisa daftar tanpa akun (guest),
     * jadi user_id harus boleh null. FK diubah dari cascadeOnDelete jadi
     * nullOnDelete supaya kalau user dihapus, data registrasinya tetap ada
     * (cuma user_id-nya jadi null), bukan ikut kehapus.
     *
     * CATATAN: migration ini butuh package doctrine/dbal untuk method ->change().
     * Kalau belum ada, jalankan dulu: composer require doctrine/dbal --dev
     */
    public function up(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('registrations', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->change();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('registrations', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable(false)->change();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }
};
