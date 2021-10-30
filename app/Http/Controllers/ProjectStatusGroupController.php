<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectStatusGroupRequest;
use App\Http\Requests\UpdateProjectStatusGroupRequest;
use App\Models\Project;
use App\Models\ProjectStatusGroup;
use Illuminate\Http\Request;

class ProjectStatusGroupController extends Controller
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
     * @param  \Illuminate\Http\Request  $request
     * @param \App\Models\Project $project
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProjectStatusGroupRequest $request, Project $project)
    {
        $exists = ProjectStatusGroup::where('project_id', $project->id)
                                    ->where('name', $request->name)
                                    ->exists();
        if ($exists)
            abort(400, 'Status group need to be unique!');
        
        $latestId = $project->statusGroups->last()->order;

        $statusGroup = $project->statusGroups()->create([
            'name' => $request->name,
            'order' => $latestId + 1,
            'project_id' => $project->id
        ]);

        return response($statusGroup);
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
     * @param  int  $projectId
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProjectStatusGroupRequest $request, $projectId)
    {
        foreach($request->groups as $order => $groupId) {
            ProjectStatusGroup::where('id', $groupId)
                                ->update([
                                    'order' => $order + 1
                                ]);
        }

        return response('Order updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $projectId
     * @param int $groupId
     * @return \Illuminate\Http\Response
     */
    public function destroy($projectId, $groupId)
    {
        ProjectStatusGroup::where('project_id', $projectId)
                            ->where('id', $groupId)
                            ->delete();

        return response('Delete success');
    }
}
