<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'from', 'to', 'created_by', 'project_manager', 'is_starred'];

    protected $casts = [
        'is_starred' => 'boolean'
    ];

    protected static function boot () {
        parent::boot();

        static::creating(function ($query) {
            $query->slug = Str::slug($query->name);
        });
    }

    public function getRouteKeyName () {
        return 'slug'; 
    }

    public function manager () {
        return $this->belongsTo(User::class, 'project_manager', 'id');
    }

    public function members () {
        return $this->belongsToMany(User::class, 'project_members');
    }
}
