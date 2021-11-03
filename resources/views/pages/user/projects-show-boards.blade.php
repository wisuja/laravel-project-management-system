@extends('layouts.project')

@section('__content')
  {{ Breadcrumbs::render('project', $project) }}
  <div class="boards-container">
    <div class="status-group-board" id="no-status">
      <h5>No Status</h5>
      @foreach ($project->sprint->noStatusTasks as $task)
        <div class="todo">
          {{ $task->title }}
        </div>
      @endforeach
    </div>
    @foreach ($project->statusGroups as $group)
      <div class="status-group-board" id="{{ $group->id }}">
        <h5>{{ $group->name }}</h5>
        @foreach ($group->tasks as $task)
          <div class="todo">
            {{ $task->title }}
          </div>
        @endforeach
      </div>
    @endforeach
  </div>
@endsection

@section('__scripts')
  <script>
    $('.status-group-board').sortable({
      connectWith: '.status-group-board',
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