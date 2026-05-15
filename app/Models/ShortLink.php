<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShortLink extends Model
{
    protected $fillable = [
        'code',
        'original_url',
        'clicks',
        'is_active',
    ];

    protected $appends = ['short_url'];

    public function getShortUrlAttribute(): string
    {
        return config('app.url') . '/' . $this->code;
    }
}
