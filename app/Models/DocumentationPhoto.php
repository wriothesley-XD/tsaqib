<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentationPhoto extends Model
{
    protected $fillable = [
        'activity_documentation_id',
        'image_path',
        'caption',
    ];

    public function documentation(): BelongsTo
    {
        return $this->belongsTo(ActivityDocumentation::class, 'activity_documentation_id');
    }
}
