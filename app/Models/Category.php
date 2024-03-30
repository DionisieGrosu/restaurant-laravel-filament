<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Category extends Model
{
    use HasFactory, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'seo_title',
        'seo_desc',
        'seo_keywords',
        'is_active',
        'on_main',
        'icon',
        'slug',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
