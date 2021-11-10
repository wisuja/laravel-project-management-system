<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectLabelRequest;
use App\Models\Project;
use App\Models\ProjectLabel;
use App\Models\Skill;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProjectLabelController extends Controller
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
            return DataTables::of($project->labels)
                                ->addIndexColumn()
                                ->addColumn('action', function ($row) {
                                    $button = '<button class="btn w-100 btn-times" type="button" onclick="removeLabel(' . $row->id . ')"><i class="fas fa-times"></i></button>';
                                    
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
     * @param  \Illuminate\Http\Request  $request
     * @param \App\Models\Project $project
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProjectLabelRequest $request, Project $project)
    {
        if ($request->ajax()) {
            $skill = Skill::where('name', $request->name)->first() ?? null;

            if (!$skill)
                $skill = Skill::create([
                    'name' => $request->name
                ]);
            else {
                $projectLabelExists = ProjectLabel::whereHas('skill', function ($query) use ($request) {
                                            return $query->where('name', $request->name);
                                        })
                                        ->where('project_id', $project->id)
                                        ->exists();

                if ($projectLabelExists)
                    abort(400, 'Label already in project');
            }

            $project->labels()->attach($skill->id);

            return response($skill);
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
     * @param int $labelId
     * @return \Illuminate\Http\Response
     */
    public function destroy($projectId, $labelId)
    {
        ProjectLabel::where('project_id', $projectId)
                    ->where('skill_id', $labelId)
                    ->delete();

        return response('Delete success');
    }
}
