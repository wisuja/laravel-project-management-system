<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProjectMember extends Pivot
{
    protected $table = 'project_members';
    
    public function project () {
        return $this->belongsTo(Project::class);
    }

    public function user () {
        return $this->belongsTo(User::class);
    }

    public function lead () {
        return $this->belongsTo(User::class, 'lead', 'id');
    }
}
