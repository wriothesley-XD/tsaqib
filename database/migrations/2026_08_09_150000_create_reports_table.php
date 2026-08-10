<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel reports: laporan konten (post/comment) dari user untuk admin moderasi.
     * Polymorphic (reportable_type/id). Status pending -> resolved.
     * unique(reporter, item) -> satu laporan per user per konten.
     */
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporter_user_id')->constrained('users')->cascadeOnDelete();
            $table->string('reportable_type');                 // 'post' | 'comment' (morph map)
            $table->unsignedBigInteger('reportable_id');
            $table->string('reason')->default('Spam / penyalahgunaan');
            $table->string('status')->default('pending');      // 'pending' | 'resolved'
            $table->timestamps();

            $table->unique(['reporter_user_id', 'reportable_type', 'reportable_id']);
            $table->index(['reportable_type', 'reportable_id']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
