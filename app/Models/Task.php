<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;

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
        'label_id',
        'linked_task',
        'parent_id',
        'created_by',
    ];

    protected $casts = [
        'is_done' => 'boolean'
    ];

    protected static function boot () {
        parent::boot();

        static::created(function ($model) {
            $model->code = strtolower($model->project->code . '-' . $model->id);
            $model->save();
        });
    }

    public function getRouteKeyName()
    {
        return 'code';
    }

    public function project () {
        return $this->belongsTo(Project::class);
    }

    public function statusGroup () {
        return $this->belongsTo(ProjectStatusGroup::class);
    }

    public function sprint () {
        return $this->belongsTo(Sprint::class);
    }

    public function type () {
        return $this->belongsTo(TaskType::class, 'task_type_id', 'id');
    }

    public function label () {
        return $this->belongsTo(ProjectLabel::class, 'label_id');
    }

    public function assignments () {
        return $this->belongsToMany(User::class, 'task_assignments')->using(TaskAssignment::class);
    }
}
