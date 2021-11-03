<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectSprintRequest;
use App\Models\Project;
use App\Models\Sprint;
use Illuminate\Http\Request;

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
