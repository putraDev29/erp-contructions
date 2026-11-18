<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\ProjectTask;
use App\Models\ProjectTaskUpdate;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Exception;

class ProjectTaskController extends Controller
{
    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'task_code' => ['required', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:255'],
            'weight_percent' => ['required', 'numeric', 'min:0', 'max:100'],
            'planned_volume' => ['required', 'numeric', 'min:0'],
            'unit' => ['required', 'string', 'max:20'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'assigned_user_id' => ['nullable', 'exists:users,id'],
            'description' => ['nullable', 'string'],
        ]);

        // Check total weight
        $existingWeight = (float) $project->tasks()->sum('weight_percent');
        $newWeight = $existingWeight + (float) $validated['weight_percent'];

        if ($newWeight > 100.01) {
            return back()->with('error', "Total bobot WBS melebihi 100%! Saat ini: {$existingWeight}%, Ditambah: {$validated['weight_percent']}%");
        }

        $task = $project->tasks()->create(array_merge($validated, [
            'actual_volume' => 0,
            'progress_percent' => 0,
            'status' => 'pending',
        ]));

        ActivityLog::record(
            'created',
            'ProjectTask',
            $task->id,
            "Menambahkan item WBS: {$task->task_code} - {$task->name} (Bobot: {$task->weight_percent}%)"
        );

        return back()->with('success', "Item WBS {$task->task_code} berhasil ditambahkan.");
    }

    public function updateProgress(Request $request, ProjectTask $task)
    {
        $validated = $request->validate([
            'progress_percent' => ['required', 'numeric', 'min:0', 'max:100'],
            'actual_volume' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'max:5120'], // Max 5MB
        ]);

        DB::transaction(function () use ($task, $validated, $request) {
            $oldProgress = (float) $task->progress_percent;
            $newProgress = (float) $validated['progress_percent'];
            $incremental = $newProgress - $oldProgress;

            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('project_progress', 'public');
            }

            // Record task update history
            ProjectTaskUpdate::create([
                'project_task_id' => $task->id,
                'update_date' => date('Y-m-d'),
                'reported_by' => auth()->id(),
                'incremental_progress' => $incremental,
                'cumulative_progress' => $newProgress,
                'notes' => $validated['notes'] ?? null,
                'photo_path' => $photoPath,
            ]);

            // Update Task status
            $status = 'in_progress';
            if ($newProgress >= 100) {
                $status = 'completed';
            } elseif ($newProgress == 0) {
                $status = 'pending';
            }

            $task->update([
                'progress_percent' => $newProgress,
                'actual_volume' => $validated['actual_volume'] ?? $task->actual_volume,
                'status' => $status,
            ]);

            // Recalculate Weighted Physical Progress of the Project
            $project = $task->project;
            $totalWeightedProgress = 0;
            foreach ($project->tasks as $t) {
                $weight = (float) $t->weight_percent;
                $prog = (float) $t->progress_percent;
                $totalWeightedProgress += ($weight * ($prog / 100));
            }

            $project->update([
                'physical_progress_percent' => round($totalWeightedProgress, 2),
            ]);

            ActivityLog::record(
                'updated',
                'ProjectTask',
                $task->id,
                "Update progres WBS {$task->task_code}: {$newProgress}%. Total progres fisik proyek: {$project->physical_progress_percent}%"
            );
        });

        return back()->with('success', 'Progres pekerjaan berhasil diperbarui.');
    }
}
