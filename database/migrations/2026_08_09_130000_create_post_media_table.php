<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel post_media: lampiran multi-media per postingan (foto/video).
     * path relatif terhadap disk 'public' (mis. posts/images/x.jpg).
     * Migrasi ini juga membackfill image_path lama dari tabel posts agar
     * postingan yang sudah ada tetap menampilkan fotonya.
     */
    public function up(): void
    {
        Schema::create('post_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->string('type'); // 'image' | 'video'
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();

            $table->index(['post_id', 'order']);
        });

        // Backfill: image_path lama -> baris post_media (type image, order 0).
        $now = now();
        foreach (DB::table('posts')->whereNotNull('image_path')->orderBy('id')->get() as $post) {
            DB::table('post_media')->insert([
                'post_id' => $post->id,
                'path' => $post->image_path,
                'type' => 'image',
                'order' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('post_media');
    }
};
