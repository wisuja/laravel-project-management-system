<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectSprintRequest;
use App\Models\Project;
use App\Models\ProjectStatusGroup;
use App\Models\SkillExperience;
use App\Models\Sprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectSprintController extends Controller
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
     * @param \App\Http\Requests\StoreProjectSprintRequest $request
     * @param \App\Models\Project $project
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProjectSprintRequest $request, Project $project)
    {
        [$from, $to] = explode(' - ', $request->dates);
        
        $sprintId = Sprint::create([
            'name' => $request->name,
            'from' => $from,
            'to' => $to,
            'created_by' => auth()->id(),
        ])->id;

        $project->update([
            'sprint_id' => $sprintId
        ]);

        return redirect()->route('projects.show', ['project' => $project, 'type' => 'backlog']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
     * @param \App\Models\Project $project
     * @param \App\Models\Sprint $sprint
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        $isDoneId = $project->statusGroups()->where('name', 'Done')->value('id');
        $doneTasks = $project->sprint->tasks()->where('status_group_id', $isDoneId)->get();
        $skillExperiences = SkillExperience::all();

        foreach ($doneTasks as $task) {
            $task->update([
                'is_archived' => true
            ]);

            foreach ($task->assignments as $user) {
                $userSkill = $user->skills()->wherePivot('skill_id', $task->label->skill_id)->first() ?? null;

                if (!$userSkill) {
                    $user->skills()->attach($task->label->skill_id);

                    $userSkill = $user->skills()->wherePivot('skill_id', $task->label->skill_id)->first();
                }

                $exp = $userSkill->pivot->experience + 1;
                $level = $skillExperiences->where('min_exp', '<', $exp)->first()->level;

                $user->skills()->updateExistingPivot($task->label->skill_id, [
                    'experience' => $exp,
                    'level' => $level
                ]);
            }
        }

        $project->sprint->tasks()->update([
            'sprint_id' => NULL
        ]);

        $project->sprint()->update([
            'is_completed' => true,
        ]);

        $project->update([
            'sprint_id' => NULL
        ]);

        return response('Sprint completed');
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
