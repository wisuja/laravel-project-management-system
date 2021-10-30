<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchUserRequest;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProjectMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \App\Models\Project $project
     * @return \Illuminate\Http\Response
     */
    public function index(Project $project)
    {
        if (request()->ajax()) {
            return DataTables::of($project->members)
                                ->addIndexColumn()
                                ->addColumn('action', function ($row) {
                                    $button = '&nbsp;';
                                    if ($row->id != auth()->id()) 
                                        $button = '<button class="btn w-100 btn-times" type="button" onclick="removeMember(' . $row->id . ')"><i class="fas fa-times"></i></button>';
                                    
                                    return $button;
                                })
                                ->rawColumns(['action'])
                                ->make(true);
        }
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
     * @param  \App\Http\Requests\SearchUserRequest  $request
     * @param \App\Models\Project $project
     * @return \Illuminate\Http\Response
     */
    public function store(SearchUserRequest $request, Project $project)
    {
        if ($request->ajax()) {
            $user = User::search($request->name)->first();

            if (!$user) 
                abort(404);
    
            $member = $project->members->where('user_id', $user->id)->first();
            if ($member)
                return response('User already in the project', 400);

            $user = User::where('name', $user->name)->first();
            $user->projects()->attach($project->id);

            return response($user);
        }
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
     * @param int $projectId
     * @param int $userId
     * @return \Illuminate\Http\Response
     */
    public function destroy($projectId, $userId)
    {
        $project = Project::whereId($projectId)->firstOrFail();

        $project->members()->detach($userId);

        if (request()->ajax())
            return response('Delete success', 200);
    }
}
