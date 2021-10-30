<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use AjCastro\EagerLoadPivotRelations\EagerLoadPivotTrait;

use Illuminate\Support\Str;

class Project extends Model
{
    use HasFactory, EagerLoadPivotTrait;

    protected $fillable = [
        'name', 
        'code', 
        'from', 
        'to', 
        'created_by'
    ];

    protected $dates = [
        'from',
        'to',
        'created_at', 
        'updated_at'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected static function boot () {
        parent::boot();

        static::creating(function ($query) {
            $query->slug = Str::slug($query->name);
        });

        static::created(function ($model) {
            $model->statusGroups()->insert([
                ['name' => 'Not Started', 'order' => 1, 'project_id' => $model->id],
                ['name' => 'In Progress', 'order' => 2, 'project_id' => $model->id],
                ['name' => 'Done', 'order' => 3, 'project_id' => $model->id],
            ]);
        });

        static::updating(function ($query) {
            $query->slug = Str::slug($query->name);
        });
    }

    public function getRouteKeyName () {
        return 'slug'; 
    }

    public function members () {
        return $this->belongsToMany(User::class, 'project_members')
                    ->withPivot('lead', 'is_starred')
                    ->using(ProjectMember::class);
    }

    public function statusGroups () {
        return $this->hasMany(ProjectStatusGroup::class)->orderBy('order', 'ASC');
    }

    public function tasks () {
        return $this->hasMany(Task::class);
    }
}
