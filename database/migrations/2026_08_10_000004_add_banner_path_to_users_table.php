<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
|------------------------------------------------------------------------------
| Kolom `banner_path` pada users — foto banner/cover profil (rasio 4:1).
|------------------------------------------------------------------------------
| Disimpan di disk 'public' (banners/...) saat user mengunggah via modal
| Edit Profil. NULL = pakai gradient emerald default. Guard hasColumn agar
| idempoten, mengikuti pola migration add_bio_and_avatar.
*/
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'banner_path')) {
                $table->string('banner_path')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'banner_path')) {
                $table->dropColumn('banner_path');
            }
        });
    }
};
