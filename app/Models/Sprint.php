<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sprint extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'from',
        'to',
        'is_completed',
        'created_by'
    ];

    protected $casts = [
        'is_completed' => 'boolean'
    ];

    protected $dates = [
        'from',
        'to'
    ];

    public function creator () {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function tasks () {
        return $this->hasMany(Task::class)->orderBy('order', 'ASC');
    }

    public function noStatusTasks () {
        return $this->hasMany(Task::class)->whereNull('status_group_id')->orderBy('order', 'ASC');
    }

    public function projects () {
        return $this->hasMany(Project::class);
    }
}
