<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function galleries()
    {
        return $this->hasMany(ProjectGallery::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
