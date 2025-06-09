<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Contact extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'user_id', 'name', 'email', 'phone', 'gender', 'custom_fields',
        'is_merged', 'merged_into'
    ];

    protected $casts = [
        'custom_fields' => 'array',
        'is_merged' => 'boolean',
    ];

    public function getProfileImageUrlAttribute()
    {
        $media = $this->getMedia('profile_images')->first();
        return $media ? $media->getFullUrl() : null;
    }
    
    public function getAdditionalFileUrlAttribute()
    {
        $media = $this->getMedia('additional_files')->first();
        return $media ? $media->getFullUrl() : null;
    }
}
