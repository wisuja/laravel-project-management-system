@extends('layouts.project')

@section('title')
  {{  $task->title }}
@endsection

@section('__content')
  {{ Breadcrumbs::render('task', $project, $task) }}
  <div class="form-group">
    <label for='task_type_id'>Task Type</label>
    <input type="text" name="task_type_id" id="task_type_id" class="form-control-plaintext" readonly value="{{ $task->type->name }}">
  </div>
  <div class="form-group">
    <label for='status'>Status</label>
    <input type="text" name="status" id="status" class="form-control-plaintext" readonly value="{{ $task->statusGroup ? $task->statusGroup->name : 'No Status' }}">
  </div>
  <div class="form-group">
    <label for='title'>Title</label>
    <input type='text' name='title' id='title' class='form-control-plaintext' value="{{ $task->title }}" readonly>
  </div>
  <div class="form-group">
    <label for='description'>Description</label>
    <div id="description" class="description-box">
      {!! $task->description !!}
    </div>
  </div>
  <div class="form-group">
    <label for='assigned_to'>Assigned to</label>
    @foreach ($task->assignments as $user)
      <a href="#" id="assigned_to" class="d-block">{{ $user->name }}</a>
    @endforeach
  </div>
  <div class="form-group">
    <label for='label'>Label</label>
    <input type='search' name='label' id='label' class='form-control-plaintext' readonly value="{{ $task->label->name }} ">
  </div>
  <div class="form-group">
    <label for='deadline'>Deadline</label>
    <input type='datetime-local' name='deadline' id='deadline' class='form-control-plaintext' value="{{ \Carbon\Carbon::parse($task->deadline)->format('Y-m-d\TH:i:s') }}" readonly>
  </div>
  <div class="form-group">
    <label for='created_by'>Created By</label>
    <input type="text" name="created_by" id="created_by" class="form-control-plaintext" readonly value="{{ $task->creator->name }}">
  </div>
  <div class="form-group">
    <a href="{{ route('projects.show', ['project' => $project]) }}" class="btn btn-secondary">Back</a>
    <a href="{{ route('projects.tasks.edit', ['project' => $project, 'task' => $task]) }}" class="btn btn-primary">Edit</a>
  </div>
  <h3>Comment Section</h3>
  <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Quod, magni.</p>
@endsection

@section('__scripts') 
@endsection