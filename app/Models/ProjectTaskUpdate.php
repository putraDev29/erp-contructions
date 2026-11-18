<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectTaskUpdate extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_task_id',
        'update_date',
        'reported_by',
        'incremental_progress',
        'cumulative_progress',
        'notes',
        'photo_path',
    ];

    protected function casts(): array
    {
        return [
            'update_date' => 'date',
            'incremental_progress' => 'decimal:2',
            'cumulative_progress' => 'decimal:2',
        ];
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(ProjectTask::class, 'project_task_id');
    }

    public function reportedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }
}
