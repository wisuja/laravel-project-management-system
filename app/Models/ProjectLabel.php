<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectLabel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'project_id'
    ];

    public $timestamps = false;

    public function project () {
        return $this->belongsTo(Project::class);
    }

    public function tasks () {
        return $this->hasMany(Task::class, 'label_id');
    }

    public function users () {
        return $this->belongsToMany(User::class, 'user_skills', 'user_id', 'skill_id')
                    ->withPivot('level', 'experience')
                    ->using(UserSkill::class);
    }
}
