<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Translation extends Model
{
    use HasFactory;

    protected $fillable = [
        'locale',
        'translation_key',
        'content',
    ];

    /**
     * A translation can have many tags.
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'tag_translation');
    }
}
