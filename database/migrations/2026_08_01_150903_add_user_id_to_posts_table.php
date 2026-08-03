<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            if (! Schema::hasColumn('posts', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
            }
            if (! Schema::hasColumn('posts', 'community_slug')) {
                $table->string('community_slug')->nullable();
            }
            if (! Schema::hasColumn('posts', 'title')) {
                $table->string('title')->nullable();
            }
            if (! Schema::hasColumn('posts', 'content')) {
                $table->text('content')->nullable();
            }
            if (! Schema::hasColumn('posts', 'image_path')) {
                $table->string('image_path')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            if (Schema::hasColumn('posts', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
            if (Schema::hasColumn('posts', 'community_slug')) {
                $table->dropColumn('community_slug');
            }
            if (Schema::hasColumn('posts', 'title')) {
                $table->dropColumn('title');
            }
            if (Schema::hasColumn('posts', 'content')) {
                $table->dropColumn('content');
            }
            if (Schema::hasColumn('posts', 'image_path')) {
                $table->dropColumn('image_path');
            }
        });
    }
};
