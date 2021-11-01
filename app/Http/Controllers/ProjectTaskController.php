<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectTaskRequest;
use App\Models\Project;
use App\Models\ProjectLabel;
use App\Models\Task;
use App\Models\TaskType;
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

        $labelId = ProjectLabel::where('name', $request->label)
                                ->where('project_id', $project->id)
                                ->value('id');

        if (!$labelId) {
            $labelId = ProjectLabel::create([
                'name' => $request->label,
                'project_id' => $project->id
            ])->id;
        }

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'deadline' => $request->deadline,
            'project_id' => $project->id,
            'task_type_id' => $request->task_type_id,
            'label_id' => $labelId,
            'created_by' => auth()->id(),
        ]);

        foreach ($request->assigned_to as $userId) {
            $task->assignments()->attach($userId);
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
        $taskTypes = TaskType::all();

        return view('pages.user.projects-tasks-show', compact('project', 'task', 'taskTypes'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
