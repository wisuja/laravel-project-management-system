<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'by',
        'parent_id'
    ];

    public function creator () {
        return $this->belongsTo(User::class, 'by');
    }

    public function parent () {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function children () {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function tasks () {
        return $this->belongsToMany(Task::class, 'task_comments', 'task_id', 'comment_id')->using(TaskComment::class);
    }
}
