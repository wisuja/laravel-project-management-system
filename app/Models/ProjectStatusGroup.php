<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectStatusGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'order',
        'project_id'
    ];

    public $timestamps = false;

    public function project () {
        return $this->belongsTo(Project::class);
    }

    public function tasks () {
        return $this->hasMany(Task::class);
    }
}
