<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Channel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'channel_logo_image',
        'streaming_url',
        'category'
    ];

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($model) {
            if ($model->isDirty('channel_logo_image') && ($model->getOriginal('channel_logo_image') !== null)) {
                Storage::disk('public')->delete($model->getOriginal('channel_logo_image'));
            }
        });

    }

}
