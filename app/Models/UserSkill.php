<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UserSkill extends Pivot
{
    use HasFactory;

    protected $table = 'user_skills';

    protected $fillable = [
        'user_id',
        'skill_id',
        'level',
        'experience'
    ];

    public function user () {
        return $this->belongsTo(User::class);
    }

    public function skill () {
        return $this->belongsTo(ProjectLabel::class, 'skill_id');
    }
}
