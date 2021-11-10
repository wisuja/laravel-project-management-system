<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProjectLabel extends Pivot
{
    use HasFactory;

    protected $table = 'project_labels';
    protected $fillable = [
        'project_id',
        'skill_id',
    ];

    public $timestamps = false;

    public function project () {
        return $this->belongsTo(Project::class);
    }

    public function skill () {
        return $this->belongsTo(Skill::class);
    }
}
