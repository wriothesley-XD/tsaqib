<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Menambahkan kolom yang belum ada di migration awal Rifki:
     * - user_id: relasi ke tabel users, wajib untuk tahu siapa yang mendaftar.
     * - status: dipakai fitur admin untuk menandai pending/reviewed.
     * - email_sent_at: menandai apakah notifikasi email sudah terkirim ke FSI.
     */
    public function up(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->foreignId('user_id')->after('id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('pending')->after('reason');
            $table->timestamp('email_sent_at')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'status', 'email_sent_at']);
        });
    }
};
