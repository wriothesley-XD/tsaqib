<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Counter cache di tabel posts (upvotes / downvotes).
     * Dipakai untuk menampilkan jumlah cepat & mengurutkan tab Terpopuler
     * tanpa aggregate per-request. Dijaga sinkron oleh PostController::vote().
     * Dijaga Schema::hasColumn agar idempoten sesuai gaya migration existing.
     */
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            if (! Schema::hasColumn('posts', 'upvotes')) {
                $table->unsignedInteger('upvotes')->default(0)->after('image_path');
            }
            if (! Schema::hasColumn('posts', 'downvotes')) {
                $table->unsignedInteger('downvotes')->default(0)->after('upvotes');
            }
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            if (Schema::hasColumn('posts', 'upvotes')) {
                $table->dropColumn('upvotes');
            }
            if (Schema::hasColumn('posts', 'downvotes')) {
                $table->dropColumn('downvotes');
            }
        });
    }
};
