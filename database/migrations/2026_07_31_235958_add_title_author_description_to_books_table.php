<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            if (! Schema::hasColumn('books', 'title')) {
                $table->string('title')->after('id');
            }
            if (! Schema::hasColumn('books', 'author')) {
                $table->string('author')->after('title');
            }
            if (! Schema::hasColumn('books', 'description')) {
                $table->text('description')->nullable()->after('author');
            }
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn(['title', 'author', 'description']);
        });
    }
};