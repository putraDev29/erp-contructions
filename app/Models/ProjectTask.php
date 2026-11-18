<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'parent_id',
        'task_code',
        'name',
        'description',
        'planned_volume',
        'actual_volume',
        'unit',
        'weight_percent',
        'start_date',
        'end_date',
        'progress_percent',
        'assigned_user_id',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'planned_volume' => 'decimal:4',
            'actual_volume' => 'decimal:4',
            'weight_percent' => 'decimal:2',
            'progress_percent' => 'decimal:2',
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(ProjectTask::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(ProjectTask::class, 'parent_id');
    }

    public function updates(): HasMany
    {
        return $this->hasMany(ProjectTaskUpdate::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }
}
