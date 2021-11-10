<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectTaskRequest;
use App\Http\Requests\UpdateProjectTaskRequest;
use App\Models\Project;
use App\Models\ProjectLabel;
use App\Models\Skill;
use App\Models\Task;
use App\Models\TaskType;
use App\Models\User;
use App\Notifications\TaskAssignedNotification;
use Illuminate\Http\Request;

class ProjectTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreProjectTaskRequest $request
     * @param \App\Models\Project $project
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProjectTaskRequest $request, Project $project)
    {
        preg_match_all('/<img[^>]+src="([^">]+)"/', $request->description, $matches);
        $attachments = [];

        foreach ($matches[1] as $imageUrl) {
            [, $image] = explode('/storage/', $imageUrl);
            $attachments[] = $image;
        }

        $skill = Skill::where('name', $request->label)->first() ?? null;

        if (!$skill) {
            $skill = Skill::create([
                'name' => $request->name
            ]);
        }

        $projectLabel = ProjectLabel::where('skill_id', $skill->id)
                                        ->where('project_id', $project->id)
                                        ->first() ?? null;

        if (!$projectLabel) {
            $project->labels()->attach($skill->id);

            $projectLabel = ProjectLabel::where('skill_id', $skill->id)
                                        ->where('project_id', $project->id)
                                        ->first();
        }

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'deadline' => $request->deadline,
            'project_id' => $project->id,
            'task_type_id' => $request->task_type_id,
            'label_id' => $projectLabel->skill_id,
            'created_by' => auth()->id(),
        ]);

        foreach ($request->assigned_to as $userId) {
            $task->assignments()->attach($userId);

            $user = User::where('id', $userId)->first();
            $user->notify(new TaskAssignedNotification($project, $task, auth()->user()));
        }

        foreach ($attachments as $attachment) {
            $task->attachments()->insert([
                'task_id' => $task->id,
                'attachment' => $attachment
            ]);
        }

        return redirect()->route('projects.show', ['project' => $project]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project, Task $task)
    {
        return view('pages.user.projects-tasks-show', compact('project', 'task'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Project $project
     * @param \App\Models\Task $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project, Task $task)
    {
        $taskTypes = TaskType::all();
        return view('pages.user.projects-tasks-edit', compact('project', 'task', 'taskTypes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateProjectTaskRequest  $request
     * @param \App\Models\Project $project
     * @param \App\Models\Task $task
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProjectTaskRequest $request, Project $project, Task $task = null)
    {
        if ($request->ajax()) {
            $updateParameters = [];
            if ($request->page == 'backlog')
                $updateParameters['sprint_id'] = $request->type == 'sprint' ? $project->sprint->id : NULL;
            else
                $updateParameters['status_group_id'] = $request->status_group == 'no_status' ? 
                                                        NULL : 
                                                        $project->statusGroups()
                                                                ->where('id', $request->status_group)
                                                                ->value('id');

            foreach ($request->order as $index => $taskId) {
                Task::where('project_id', $project->id)
                    ->where('id', $taskId)
                    ->update(array_merge([
                        'order' => $index + 1
                    ], $updateParameters));
            };

            return response('Order success');
        } else {
            preg_match_all('/<img[^>]+src="([^">]+)"/', $request->description, $matches);
            $attachments = [];
    
            foreach ($matches[1] as $imageUrl) {
                [, $image] = explode('/storage/', $imageUrl);
                $attachments[] = $image;
            }

            $skill = Skill::where('name', $request->label)->first() ?? null;

            if (!$skill) {
                $skill = Skill::create([
                    'name' => $request->name
                ]);
            }

            $projectLabel = ProjectLabel::where('skill_id', $skill->id)
                                            ->where('project_id', $project->id)
                                            ->first() ?? null;

            if (!$projectLabel) {
                $project->labels()->attach($skill->id);

                $projectLabel = ProjectLabel::where('skill_id', $skill->id)
                                            ->where('project_id', $project->id)
                                            ->first();
            }
    
            $task->update([
                'title' => $request->title,
                'description' => $request->description,
                'deadline' => $request->deadline,
                'task_type_id' => $request->task_type_id,
                'label_id' => $projectLabel->skill_id,
            ]);
    
            foreach ($request->assigned_to as $userId) {
                $task->assignments()->updateExistingPivot($task->id, [
                    'user_id' => $userId
                ]);

                $user = User::where('id', $userId)->first();
                $user->notify(new TaskAssignedNotification($project, $task, auth()->user()));
            }
    
            $task->attachments()->delete();
            foreach ($attachments as $attachment) {
                $task->attachments()->insert([
                    'task_id' => $task->id,
                    'attachment' => $attachment
                ]);
            }
    
            return redirect()->route('projects.tasks.show', ['project' => $project, 'task' => $task]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $projectId
     * @param int $taskId
     * @return \Illuminate\Http\Response
     */
    public function destroy($projectId, $taskId)
    {
        Task::where('id', $taskId)
            ->where('project_id', $projectId)
            ->delete();

        return response('Delete success');
    }
}
