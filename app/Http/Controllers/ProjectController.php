<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchUserRequest;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use App\Models\ProjectMember;
use App\Models\ProjectStatusGroup;
use App\Models\TaskType;
use App\Models\User;
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
            $projects = ProjectMember::with(['project', 'leader'])->where('user_id', auth()->id())->get();

            return DataTables::of($projects)
                                ->addColumn('star', function ($row) {
                                    $star = '<span role="button" id="star-' . $row->project->id . '" onclick="toggleStar(' . $row->project->id . ')">';
                                    $star .= '<i class="' . ($row->project->is_starred ? 'fas text-warning' : 'far text-dark') . ' fa-star"></i>';
                                    $star .= '</span>';  
                                    
                                    return $star;
                                })
                                ->addColumn('name', function ($row) {
                                    $name = '<a href="' . route('projects.show', ['project' => $row->project]) . '">' . $row->project->name . '</a>';

                                    return $name;
                                })
                                ->addColumn('leader', function ($row) {
                                    $name = '<a href="#">' . $row->leader->name . '</a>';

                                    return $name;
                                })
                                ->addColumn('action', function ($row) {
                                    $action = '<div class="dropdown">';
                                    $action .= '<button class="btn btn-light" type="button" data-toggle="dropdown">';
                                    $action .= '<i class="fas fa-ellipsis-h"></i>';
                                    $action .= '</button>';
                                    $action .= '<div class="dropdown-menu">';
                                    $action .= '<a href="#" class="dropdown-item" onclick="remove(' . $row->project->id . ')">Delete</a>';
                                    $action .= '</div>';
                                    $action .= '</div>';
                                    
                                    return $action;
                                })
                                ->rawColumns(['star', 'name', 'leader', 'action'])
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
            'from' => $from,
            'to' => $to,
            'created_by' => auth()->id()    
        ]);

        $project->members()->attach(auth()->id(), ['lead' => auth()->id()]);

        return redirect()->route('projects.show', ['project' => $project]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Project $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project, $type = 'backlog')
    {
        $this->updateRecentProjects($project);

        switch ($type) {
            case 'backlog':
                $tasks = [];
                $taskTypes = TaskType::all();
                return view('pages.user.projects-show-backlog', compact('project', 'tasks', 'taskTypes'));
                break;
            case 'boards':
                return view('pages.user.projects-show-boards', compact('project'));
                break;
            case 'setting':
                return view('pages.user.projects-show-setting', compact('project'));
                break;
            default:
                abort(404);
                break;
        }
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
        $project = Project::whereId($request->id)->firstOrFail();
        
        if ($request->ajax()) {
            $affectedRow = $project->members()->updateExistingPivot(auth()->id(), [
                'is_starred' => $request->is_starred
            ]);

            if ($affectedRow)
                return response('Update success', 200);
            else 
                return response('Failed to update', 400);
        }

        [$from, $to] = explode(' - ', $request->duration);

        $project->update([
            'name' => $request->name,
            'code' => $request->code,
            'from' => $from,
            'to' => $to,
        ]);

        $this->updateRecentProjects($project);

        return redirect()->route('projects.show', ['project' => $project, 'type' => 'setting']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $projectId
     * @return \Illuminate\Http\Response
     */
    public function destroy($projectId)
    {
        $project = Project::whereId($projectId)->first();
        $project->delete();

        $this->updateRecentProjects($project, true);

        if (request()->ajax())
            return response('Delete success', 200);
    }

    /**
     * Update recent projects in the cache
     * @param \App\Models\Project $project;
     */
    private function updateRecentProjects (Project $project, $isRemoving = false) {
        $recentProjects = Cache::pull('recent-projects');

        if (!$recentProjects)
            $recentProjects = Project::whereHas('members', function ($query) {
                                    $query->where('user_id', auth()->id());
                                })->get();
        else {
            if (!$isRemoving)
                $recentProjects = $recentProjects->prepend($project)->unique('name')->splice(0, 3);        
            else
                $recentProjects = $recentProjects->filter(function ($item) use ($project) {
                    return $item->name !== $project->name;
                });
        }
        
        Cache::rememberForever('recent-projects', function () use ($recentProjects) {
            return $recentProjects;
        });
    }
}
