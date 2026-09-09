<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
|------------------------------------------------------------------------------
| Video rekaman kegiatan (opsional, 1 file) pada dokumentasi kegiatan.
| Disimpan di disk 'public' (documentations/...), dirender via <video> di
| halaman detail /info/dokumentasi/{slug}.
*/

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activity_documentations', function (Blueprint $table) {
            $table->string('video_path')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('activity_documentations', function (Blueprint $table) {
            $table->dropColumn('video_path');
        });
    }
};
