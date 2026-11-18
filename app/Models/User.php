<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'phone',
        'is_active',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function projectAssignments(): HasMany
    {
        return $this->hasMany(UserProjectAssignment::class);
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'user_project_assignments');
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function hasRole(string|array $roles): bool
    {
        if (!$this->role) {
            return false;
        }

        if (is_array($roles)) {
            return in_array($this->role->name, $roles, true);
        }

        return $this->role->name === $roles;
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super_admin');
    }

    public function hasPermission(string $permissionName): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        if (!$this->role) {
            return false;
        }

        return $this->role->permissions->contains('name', $permissionName);
    }

    public function canAccessProject(int|string|null $projectId): bool
    {
        if (!$projectId) {
            return true;
        }

        // Super Admin, Direktur, Finance, Auditor can access all projects
        if ($this->hasRole(['super_admin', 'director', 'finance_accounting', 'auditor', 'procurement'])) {
            return true;
        }

        // Project Manager or Site Manager check
        $project = Project::find($projectId);
        if (!$project) {
            return false;
        }

        if ($project->project_manager_id === $this->id || $project->site_manager_id === $this->id) {
            return true;
        }

        return $this->projectAssignments()->where('project_id', $projectId)->exists();
    }
}
