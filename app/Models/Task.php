<?php

namespace App\Models;

use App\Enums\TaskStatusEnum;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'name',
        'description',
        'creator_id',
        'project_id',
        'start_date',
        'end_date',
        'status'
    ];

    protected $casts = [
        'status' => TaskStatusEnum::class,
        'start_date' => 'datetime',
        'end_date' => 'datetime'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function assignees()
    {
        return $this->belongsToMany(User::class, 'task_assignees')->withTimestamps();
    }

    public function watchers() {
        return $this->belongsToMany(User::class, 'task_watchers')->withTimestamps();
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}

