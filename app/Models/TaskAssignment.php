<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class TaskAssignment extends Pivot
{
    use HasFactory;

    protected $table = 'task_assignments';

    protected $fillable = [
        'task_id',
        'user_id'
    ];

    public $timestamps = false;

    public function task () {
        return $this->belongsTo(Task::class);
    }

    public function assignee () {
        return $this->belongsTo(User::class);
    }
}
