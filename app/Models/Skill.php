<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public $timestamps = false;

    public function users () {
        return $this->belongsToMany(User::class, 'user_skills', 'user_id', 'skill_id')
                    ->withPivot('level', 'experience')
                    ->using(UserSkill::class);
    }

    public function projects () {
        return $this->belongsToMany(Project::class, 'project_labels', 'project_id', 'skill_id')
                    ->using(ProjectLabel::class);
    }
}
