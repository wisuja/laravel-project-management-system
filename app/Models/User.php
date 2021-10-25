<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use AjCastro\EagerLoadPivotRelations\EagerLoadPivotTrait;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, EagerLoadPivotTrait;

    protected $fillable = [
        'name',
        'email',
        'password',
        'photo',
    ];

    protected $hidden = [
        'password',
        'created_at',
        'updated_at',
    ];

    public function projects () {
        return $this->belongsToMany(Project::class, 'project_members')
                    ->withPivot('lead', 'is_starred')
                    ->wherePivot('user_id', 2)
                    ->using(ProjectMember::class);
    }

    public function leader () {
        return $this->hasMany(ProjectMember::class, 'lead', 'id');
    }
}
