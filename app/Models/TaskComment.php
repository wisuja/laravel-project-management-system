<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class TaskComment extends Pivot
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'comment_id',
    ];

    public function task () {
        return $this->belongsTo(Task::class);
    }    

    public function comment () {
        return $this->belongsTo(Comment::class);
    }
}
