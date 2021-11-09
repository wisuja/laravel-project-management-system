@extends('layouts.project')

@section('__content')
  {{ Breadcrumbs::render('project', $project) }}
  <div class="d-flex justify-content-between align-items-center">
    @if (is_null($project->sprint))
      <h5>Sprint </h5>
      <button type="button" class="btn btn-primary" data-toggle='modal' data-target='#createSprintModal'>Start Sprint</button>
    @else
      <h5>Sprint (<span class="font-italic font-weight-normal">{{ $project->sprint->name }}</span>)</h5>
      <button type="button" class="btn btn-primary" onclick="completeSprint()">Complete Sprint</button>
    @endif
  </div>
  @if (is_null($project->sprint))
    <p>Please start the sprint first.</p>
  @else
    <div class="tasks-container" id="sprint">
      @foreach ($project->sprint->tasks as $task)
        <div class="todo d-flex justify-content-between align-items-center" id="{{ $task->id }}">
          <a href="{{ route('projects.tasks.show', ['project' => $project, 'task' => $task]) }}" class="text-decoration-none text-dark">
            <span>{{ $task->title }}</span>
          </a>
          <div class="dropdown">
            <button class="btn btn-sm btn-light" type="button" data-toggle="dropdown">
              <i class="fas fa-ellipsis-h"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-right">
              <span role="button" class="dropdown-item" onclick="deleteTask({{ $task->id }})">Delete</span>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  @endif
  <div class="d-flex justify-content-between align-items-center">
    <h5>Backlog</h5>
    <button class="btn btn-primary" type="button" data-toggle='modal' data-target='#createTaskModal'>
      Add Task
    </button>
  </div>
  <div class="tasks-container" id="backlog">
    @foreach ($project->backlog as $task)
      <div class="todo d-flex justify-content-between align-items-center" id="{{ $task->id }}">
        <a href="{{ route('projects.tasks.show', ['project' => $project, 'task' => $task]) }}" class="text-decoration-none text-dark">
          <span>{{ $task->title }}</span>
        </a>
        <div class="dropdown">
          <button class="btn btn-sm btn-light" type="button" data-toggle="dropdown">
            <i class="fas fa-ellipsis-h"></i>
          </button>
          <div class="dropdown-menu dropdown-menu-right">
            <span role="button" class="dropdown-item" onclick="deleteTask({{ $task->id }})">Delete</span>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <div id="createTaskModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="createTaskModalLabel">Create a new task</h5>
          <button type="button" class="close" data-dismiss="modal">
            <span>&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="{{ route('projects.tasks.store', ['project' => $project]) }}" method="POST" id="form-create-task">
            @csrf
            <div class="form-group">
              <label for='task_type_id'>Task Type</label>
              <select name='task_type_id' id='task_type_id' class='form-control' required>
                @foreach ($taskTypes as $type)
                  <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for='title'>Title</label>
              <input type='text' name='title' id='title' class='form-control' required>
            </div>
            <div class="form-group">
              <label for='description'>Description</label>
              <textarea name='description' id='description' class='form-control description'></textarea>
            </div>
            <div class="form-group">
              <label for='assigned_to'>Assigned to</label>
              <select name='assigned_to[]' id='assigned_to' class='form-control my-1' required>
                @foreach ($project->members as $member)
                  <option value="{{ $member->id }}">{{ $member->name }}</option>
                @endforeach
              </select>
              <button class="btn btn-light w-100 mt-2 text-left" type="button" id="btn-add-assignee">
                <i class="fas fa-plus mr-1"></i>
                Add Assignee
              </button>
            </div>
            <div class="form-group">
              <label for='label'>Label</label>
              <input type='search' name='label' id='label' class='form-control' list="labels" required>
            </div>
            <datalist id="labels">
              @foreach ($project->labels as $label)
                <option value="{{ $label->name }}">
              @endforeach
            </datalist>
            <div class="form-group">
              <label for='deadline'>Deadline</label>
              <input type='datetime-local' name='deadline' id='deadline' class='form-control' value="{{ \Carbon\Carbon::now()->format('Y-m-d\TH:i:s') }}" required>
              <small>Estimated time: <span id="estimated-time" class="font-weight-bold"><i class="fas fa-question-circle"></i></span></small>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" onclick="createTask()" id="btn-create-task">Create</button>
        </div>
      </div>
    </div>
  </div>

  <div id="createSprintModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="createSprintModalLabel">Create a new sprint</h5>
          <button type="button" class="close" data-dismiss="modal">
            <span>&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="{{ route('projects.sprints.store', ['project' => $project]) }}" method="POST" id="form-create-sprint">
            @csrf
            <div class="form-group">
              <label for='name'>Name</label>
              <input type='text' name='name' id='name' class='form-control' required>
            </div>
            <div class="form-group">
              <label for='dates'>Date</label>
              <input type='text' name='dates' id='dates' class='form-control' required>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" form="form-create-sprint">Create</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('__scripts')
  <script>
    function getEstimatedTime () {
      let taskType = $('#label').val();
      let numberOfPeoples = $('select[name="assigned_to[]"]').length;

      if (taskType == null || numberOfPeoples < 1)
        return;

      $.ajax({
        url: "{{ route('time-estimator.store') }}",
        method: 'POST',
        data: {
          _token: '{{ csrf_token() }}',
          taskType,
          numberOfPeoples
        },
        success: function ({prediction}) {
          $('#estimated-time').text(moment().add(prediction, 'minutes').format('LLLL'));
          $('#btn-create-task').prop('disabled', false);
        },
        error: function (error) {
          console.error(error)
          $('#btn-create-task').prop('disabled', false);
        }
      })
    }

    function createTask () {
      if ($('#estimated-time').text() != '')
        $('#form-create-task').submit();
      else {
        $('#btn-create-task').prop('disabled', true);
        getEstimatedTime();
      }
    }

    function deleteTask (taskId) {
      swal({
        text: 'Are you sure to delete this task?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Confirm',
      }, function () {
        $.ajax({
          url: '/projects/{{ $project->id }}/tasks/' + taskId,
          method: 'POST',
          data: {
            _token: '{{ csrf_token() }}',
            _method: 'DELETE',
          },
          success: function (response) {
            console.log(response);
            window.location.reload();
          },
          error: function (error) {
            console.error(error);
          }
        })
      });
    }

    function sendFile(file) {
      let data = new FormData();
      data.append("_token", '{{ csrf_token() }}');
      data.append("image", file);
      $.ajax({
        url: "{{ route('save-image') }}",
        type: "POST",
        data,
        cache: false,
        contentType: false,
        processData: false,
        success: function (url) {
          $('.description').summernote("insertImage", url);
        }
      });
    }

    $(function() {
      $('#btn-add-assignee').on('click', function () {
        let members = @json($project->members);
        let selectedMembers = $('select[name="assigned_to[]"]').map(function () {
          return this.value;
        }).get();

        let notAssignedMembers = members.filter(member => !selectedMembers.includes(member.id.toString()));

        if (notAssignedMembers.length > 0) {
          let element = `<select name='assigned_to[]' id='assigned_to' class='form-control my-1' required>`;
          
          notAssignedMembers.forEach(member => {
            element += `<option value="${member.id}">${member.name}</option>`
          });
          element += `</select>`;
          
          $(element).insertBefore(this);
        }

        getEstimatedTime();
      })

      $('#label').on('change', function () {
        getEstimatedTime();
      })

      $('.description').summernote({
        minheight: 200,
        toolbar: [
          ['control', ['undo', 'redo']],
          ['style', ['bold', 'italic', 'underline', 'strikethrough']],
          ['fontsize', ['fontsize']],
          ['color', ['color']],
          ['paragraph', ['ul', 'ol', 'paragraph']],
          ['insert', ['link', 'unlink', 'picture']],
        ],
        callbacks: {
          onImageUpload: function (files) {
            sendFile(files[0]);
          }
        }
      });

      $('.tasks-container').sortable({
        connectWith: '.tasks-container',
        update: function(e,ui) {
          let parent = ui.item.parent().get(0).id;
          let data = $(`#${parent}`).sortable('toArray');

          $.ajax({
            url: "{{ route('projects.tasks.update', ['project' => $project]) }}",
            method: 'POST',
            data: {
              _token: '{{ csrf_token() }}',
              _method: 'PUT',
              page: 'backlog',
              type: parent,
              order: data
            }
          })
        }
      });

      $('#createSprintModal input[name=dates]').daterangepicker({
        locale: {
          format: 'YYYY-MM-DD',
        }
      });
    })
  </script>
@endsection