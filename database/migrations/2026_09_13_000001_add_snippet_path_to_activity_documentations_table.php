<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Cuplikan video pendek utk widget Beranda (file kecil lokal 480-720p).
     * Terpisah dari video_path yang menyimpan link Drive utk video lengkap
     * di halaman Laboratorium PAI.
     */
    public function up(): void
    {
        Schema::table('activity_documentations', function (Blueprint $table) {
            $table->string('snippet_path')->nullable()->after('video_path');
        });
    }

    public function down(): void
    {
        Schema::table('activity_documentations', function (Blueprint $table) {
            $table->dropColumn('snippet_path');
        });
    }
};
