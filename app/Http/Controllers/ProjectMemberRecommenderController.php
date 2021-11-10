<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectMemberRecommenderRequest;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectMemberRecommenderController extends Controller
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
     * @param \App\Http\Requests\StoreProjectMemberRecommenderRequest $request
     * @param \App\Models\Project $project
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProjectMemberRecommenderRequest $request, Project $project)
    {
        $response = [
            'recommended' => false,
            'members' => $project->members
        ];

        $members = $project->members->filter(function ($member) use ($request) {
            return $member->skills()->where('name', $request->taskType)->exists();
        });

        if ($members->count() <= 0) 
            return response($response);

        $members = $members->map(function ($member) use ($request) {
            return [
                'id' => $member->id,
                'name' => $member->name,
                'level' => $member->skills()->where('name', $request->taskType)->first()->pivot->level,
                'exp' => $member->skills()->where('name', $request->taskType)->first()->pivot->experience
            ];
        })->sortByDesc('exp')->values();

        $response['recommended'] = true;
        $response['members'] = $members;

        return response($response);
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
