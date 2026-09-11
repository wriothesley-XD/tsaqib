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
        'video_path',
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
     * Video bisa lokal (disk 'public', path relatif) atau link Google Drive
     * (disimpan utuh di video_path). Ekstrak FILE_ID dari bentuk link Drive
     * mana pun (file/d/, open?id=, uc?id=) — atau ID mentah tanpa URL.
     */
    public function driveFileId(): ?string
    {
        if (! $this->video_path) {
            return null;
        }

        if (preg_match('#drive\.google\.com/(?:file/d/|open\?id=|uc\?.*?[?&]id=)([\w-]{10,})#', $this->video_path, $m)) {
            return $m[1];
        }

        // ID mentah tanpa URL (paste langsung FILE_ID).
        return preg_match('#^[\w-]{20,}$#', $this->video_path) ? $this->video_path : null;
    }

    /** True jika video di-host Google Drive → render via iframe preview, bukan <video>. */
    public function isDriveVideo(): bool
    {
        return $this->driveFileId() !== null;
    }

    /** URL iframe embed Drive (hemat storage hosting). Null jika video lokal/tidak ada. */
    public function videoPreviewUrl(): ?string
    {
        $id = $this->driveFileId();

        return $id ? "https://drive.google.com/file/d/{$id}/preview" : null;
    }

    /** URL tonton di Drive — fallback bila embed gagal dimuat. */
    public function videoWatchUrl(): ?string
    {
        $id = $this->driveFileId();

        return $id ? "https://drive.google.com/file/d/{$id}/view" : null;
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
