<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use AjCastro\EagerLoadPivotRelations\EagerLoadPivotTrait;

use Illuminate\Support\Str;

class Project extends Model
{
    use HasFactory, EagerLoadPivotTrait;

    protected $fillable = [
        'name', 
        'code', 
        'from', 
        'to', 
        'created_by'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
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

    public function members () {
        return $this->belongsToMany(User::class, 'project_members')
                    ->withPivot('lead', 'is_starred')
                    ->wherePivot('user_id', auth()->id())
                    ->using(ProjectMember::class);
    }

    public function project () {
        return $this->hasMany(ProjectMember::class);
    }
}
