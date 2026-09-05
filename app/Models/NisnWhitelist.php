<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Whitelist NISN siswa (Pintu A verifikasi). Dikelola dari Admin Panel —
 * tambah manual atau import massal dari CSV/Excel.
 *
 * @property string $nisn
 * @property string|null $nama
 * @property string|null $kelas
 */
class NisnWhitelist extends Model
{
    protected $table = 'nisn_whitelist';

    protected $fillable = ['nisn', 'nama', 'kelas'];
}
