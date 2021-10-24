<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\Facades\DataTables;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            $projects = Project::with(['manager'])->orderBy('is_starred', 'desc')->get();

            return DataTables::of($projects)
                                ->addColumn('star', function ($row) {
                                    $star = '<span role="button" id="star-' . $row->id . '" onclick="toggleStar(' . $row->id . ')">';
                                    $star .= '<i class="' . ($row->is_starred ? 'fas text-warning' : 'far text-dark') . ' fa-star"></i>';
                                    $star .= '</span>';  
                                    
                                    return $star;
                                })
                                ->addColumn('action', function ($row) {
                                    $action = '<button class="btn btn-light">';
                                    $action .= '<i class="fas fa-ellipsis-h"></i>';
                                    $action .= '</button>';
                                    
                                    return $action;
                                })
                                ->rawColumns(['star', 'action'])
                                ->make(true);
        }

        return view('pages.user.projects-index');
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
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProjectRequest $request)
    {
        [$from, $to] = explode(' - ', $request->duration);
        
        $project = Project::create([
            'name' => $request->name,
            'code' => $request->code,
            'from' => Carbon::parse($from)->format('Y-m-d'),
            'to' => Carbon::parse($to)->format('Y-m-d'),
            'project_manager' => auth()->id(),
            'created_by' => auth()->id()    
        ]);

        $project->members()->attach(auth()->id());

        return redirect()->route('projects.show', ['project' => $project]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Project $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        $recentProjects = Cache::pull('recent-projects');

        if (!$recentProjects)
            $recentProjects = Project::latest()->take(3)->get();
        else 
            $recentProjects = $recentProjects->prepend($project)->unique()->splice(0, 3);
        
        Cache::rememberForever('recent-projects', function () use ($recentProjects) {
            return $recentProjects;
        });

        return view('pages.user.projects-show', compact('project'));
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
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProjectRequest $request)
    {
        if ($request->ajax()) {
            $updateArray = [];
            foreach ($request->validated() as $key => $value) {
                if ($key != 'id')
                    $updateArray[$key] = $value;
            }

            $affectedRow = Project::whereId($request->id)->update($updateArray);

            if ($affectedRow)
                return response('Update success', 200);
            else 
                return response('Failed to update', 400);
        }
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
