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
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'nisn')) {
                $table->string('nisn', 20)->nullable()->unique()->after('email');
            }
            if (! Schema::hasColumn('users', 'nis')) {
                $table->string('nis', 20)->nullable()->index()->after('nisn');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nisn', 'nis']);
        });
    }
};
