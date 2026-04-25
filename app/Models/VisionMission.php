<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisionMission extends Model
{
    /** @use HasFactory<\Database\Factories\VisionMissionFactory> */
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'missions' => 'json',
    ];
}
