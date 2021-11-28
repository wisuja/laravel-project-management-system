@extends('layouts.project', ['page' => 'boards'])

@section('__content')
  {{ Breadcrumbs::render('project', $project) }}
  @if (is_null($project->sprint))
    <h5>Please start a sprint first</h5>
  @else
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div>
        <h5 class="mb-0">Current Sprint: {{ $project->sprint->name }}</h5>
        <span>Duration: {{ $project->sprint->from->format('d-m-Y') }} - {{ $project->sprint->to->format('d-m-Y') }}</span>
      </div>
      <button type="button" class="btn btn-primary" onclick="completeSprint()">Complete Sprint</button>
    </div>
    <div class="boards-container">
      <div class="status-group-board" id="no-status">
        <h5>No Status</h5>
        @foreach ($project->sprint->noStatusTasks as $task)
          <div class="todo" id="{{ $task->id }}">
            <a href="{{ route('projects.tasks.show', ['project' => $project, 'task' => $task]) }}" class="text-decoration-none text-dark">
              <span>{{ $task->title }}</span>
            </a>
          </div>
        @endforeach
      </div>
      @foreach ($project->statusGroups as $group)
        <div class="status-group-board" id="{{ $group->id }}">
          <h5>{{ $group->name }}</h5>
          @foreach ($group->tasks as $task)
            <div class="todo" id="{{ $task->id }}">
              <a href="{{ route('projects.tasks.show', ['project' => $project, 'task' => $task]) }}" class="text-decoration-none text-dark">
                <span>{{ $task->title }}</span>
              </a>
            </div>
          @endforeach
        </div>
      @endforeach
    </div>
  @endif
@endsection

@section('__scripts')
  <script>
    $('.status-group-board').sortable({
      connectWith: '.status-group-board',
      items: '.todo',
      update: function (event, ui) {
        let parent = ui.item.parent().get(0).id;
        let data = $(`#${parent}`).sortable('toArray');

        $.ajax({
          url: "{{ route('projects.tasks.update', ['project' => $project]) }}",
          method: 'POST',
          data: {
            _token: '{{ csrf_token() }}',
            _method: 'PUT',
            page: 'boards',
            status_group: parent,
            order: data
          }
        })
      }
    })
  </script>
@endsection