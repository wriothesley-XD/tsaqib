<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ActivityDocumentation extends Model
{
    protected $table = 'activity_documentations';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'event_date',
        'category',
    ];

    protected $casts = [
        'event_date' => 'date',
    ];

    public function photos(): HasMany
    {
        return $this->hasMany(DocumentationPhoto::class);
    }

    /**
     * Kelas badge kategori — warna dipilih deterministik dari keluarga
     * hijau-emas-krem tema (hash nama kategori), bukan warna acak.
     */
    public function badgeClass(): string
    {
        $tints = [
            'bg-[#01795F]/20 text-[#3fd6b0] border-[#01795F]/30',              // hijau
            'bg-[rgba(201,166,107,0.16)] text-[var(--gold)] border-[rgba(201,166,107,0.3)]', // emas
            'bg-[rgba(247,245,239,0.08)] text-[var(--cream)] border-[rgba(247,245,239,0.18)]', // krem
        ];

        return $tints[abs(crc32((string) $this->category)) % 3];
    }
}
