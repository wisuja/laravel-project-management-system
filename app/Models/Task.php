<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'title',
        'description',
        'deadline',
        'is_done',
        'task_type_id',
        'project_id',
        'sprint_id',
        'status_group_id',
        'label',
        'linked_task',
        'parent_id'
    ];

    protected $casts = [
        'is_done' => 'boolean'
    ];

    public function project () {
        return $this->belongsTo(Project::class);
    }

    public function statusGroup () {
        return $this->belongsTo(ProjectStatusGroup::class);
    }

    public function sprint () {
        return $this->belongsTo(Sprint::class);
    }

    public function assignments () {
        return $this->belongsToMany(User::class, 'task_assignments')->using(TaskAssignment::class);
    }
}
