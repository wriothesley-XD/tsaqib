<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    protected $table = 'tugas';

    protected $fillable = [
        'nama_siswa',
        'kelas',
        'judul',
        'file_path',
    ];
}
