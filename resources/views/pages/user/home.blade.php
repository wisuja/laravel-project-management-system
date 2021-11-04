@extends('layouts.user')

@section('title')
    Home
@endsection

@section('_content')
    <div class="container my-5">
        @if ($errors->any())
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-12">
                <h3 class="font-weight-bold">Recent Projects</h3>
            </div>
        </div>
        <div class="row">
            @forelse ($recentProjects as $project)
                <div class="col-4">
                    <a href="{{ route('projects.show', ['project' => $project]) }}">
                        <div class="card">
                            <img class="card-img" src="https://mdbcdn.b-cdn.net/img/Photos/Others/intro1.jpg">
                            <div class="card-img-overlay">
                                <h4 class="text-dark">{{ $project->name }}</h4>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12">
                    <p class="mb-0">No Recent Project</p>
                </div>
            @endforelse
        </div>
        <div class="row mt-1">
            <div class="col-12">
                <a href="{{ route('projects.index') }}" class="text-primary">See All Projects</a>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-12 table-responsive">
                <h3 class="font-weight-bold">Assigned to me</h3>
                <table class="table table-hover w-100 text-center">
                    <thead>
                        <tr>
                            <th>Deadline</th>
                            <th>Title</th>
                            <th>Project</th>
                            <th>Assignor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse (auth()->user()->tasks as $task)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($task->deadline)->diffForHumans() }}</td>
                                <td>
                                    <a href="{{ route('projects.tasks.show', ['project' => $task->project, 'task' => $task]) }}">{{ $task->title }}</a>
                                </td>
                                <td>
                                    <a href="{{ route('projects.show', ['project' => $task->project]) }}">{{ $task->project->name }}</a>
                                </td>
                                <td>{{ $task->creator->name }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">No Tasks</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection