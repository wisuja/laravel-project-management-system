<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkillExperience extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'level',
        'min_exp'
    ];

    public $timestamps = false;
}
