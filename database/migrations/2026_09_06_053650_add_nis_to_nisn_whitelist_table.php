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
        Schema::table('nisn_whitelist', function (Blueprint $table) {
            $table->string('nis', 20)->nullable()->index()->after('nisn');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nisn_whitelist', function (Blueprint $table) {
            $table->dropColumn('nis');
        });
    }
};
