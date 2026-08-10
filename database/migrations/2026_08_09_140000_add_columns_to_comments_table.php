<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel `comments` sudah ada (migration awal hanya bikin id + timestamps).
     * Tambahkan kolom nyata: relasi ke post & user, serta isi komentar.
     * Idempoten via Schema::hasColumn mengikuti gaya migration existing.
     */
    public function up(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            if (! Schema::hasColumn('comments', 'post_id')) {
                $table->foreignId('post_id')->after('id')->constrained()->cascadeOnDelete();
            }
            if (! Schema::hasColumn('comments', 'user_id')) {
                $table->foreignId('user_id')->after('post_id')->constrained()->cascadeOnDelete();
            }
            if (! Schema::hasColumn('comments', 'body')) {
                $table->text('body')->after('user_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            if (Schema::hasColumn('comments', 'post_id')) {
                $table->dropForeign(['post_id']);
                $table->dropColumn('post_id');
            }
            if (Schema::hasColumn('comments', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
            if (Schema::hasColumn('comments', 'body')) {
                $table->dropColumn('body');
            }
        });
    }
};
