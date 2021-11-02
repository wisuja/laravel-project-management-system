@extends('layouts.project')

@section('title')
  {{  $task->title }}
@endsection

@section('__content')
  {{ Breadcrumbs::render('task', $project, $task) }}
  <form action="{{ route('projects.tasks.update', ['project' => $project, 'task' => $task]) }}" method="POST" id="form-create-task">
    @csrf
    @method('PUT')
    <div class="form-group">
      <label for='task_type_id'>Task Type</label>
      <select name='task_type_id' id='task_type_id' class='form-control' required>
        @foreach ($taskTypes as $type)
          <option value="{{ $type->id }}" {{ $type->id == $task->task_type_id ? 'selected' : '' }}>{{ $type->name }}</option>
        @endforeach
      </select>
    </div>
    <div class="form-group">
      <label for='title'>Title</label>
      <input type='text' name='title' id='title' class='form-control' required value="{{ $task->title }}">
    </div>
    <div class="form-group">
      <label for='description'>Description</label>
      <textarea name='description' id='description' class='form-control description'></textarea>
    </div>
    <div class="form-group">
      <label for='assigned_to'>Assigned to</label>
      @foreach ($task->assignments as $member)
        <div class="d-flex justify-content-between align-items-center" id="member-{{ $member->id }}">
          <input type="hidden" name="assigned_to[]" value="{{ $member->id }}">
          <input type="text" id="assigned_to" value="{{ $member->name }}" class="form-control-plaintext w-75" readonly>
          <button type="button" class="close btn-remove-assignee" data-id="{{ $member->id }}">&times;</button>
        </div>
      @endforeach
      <button class="btn btn-light w-100 mt-2 text-left" type="button" id="btn-add-assignee">
        <i class="fas fa-plus mr-1"></i>
        Add Assignee
      </button>
    </div>
    <div class="form-group">
      <label for='label'>Label</label>
      <input type='search' name='label' id='label' class='form-control' list="labels" required value="{{ $task->label->name }}">
    </div>
    <datalist id="labels">
      @foreach ($project->labels as $label)
        <option value="{{ $label->name }}">
      @endforeach
    </datalist>
    <div class="form-group">
      <label for='deadline'>Deadline</label>
      <input type='datetime-local' name='deadline' id='deadline' class='form-control' value="{{ \Carbon\Carbon::parse($task->deadline)->format('Y-m-d\TH:i:s') }}" required>
    </div>
    <div class="form-group">
      <button type="reset" class="btn btn-secondary">Reset</button>
      <button type="submit" class="btn btn-primary">Update</button>
    </div>
  </form>
@endsection

@section('__scripts')
  <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
  <script>
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
        let assignedMembers = @json($task->assignments);

        let notAssignedMembers = _.intersectionWith(members, assignedMembers, _.isEqual);

        if (notAssignedMembers.length > 0) {
          let element = `<select name='assigned_to[]' id='assigned_to' class='form-control my-1' required>`;
          
          notAssignedMembers.forEach(member => {
            element += `<option value="${member.id}">${member.name}</option>`
          });
          element += `</select>`;
          
          $(element).insertBefore(this);
        } else {
          swal({
            text: 'There is no other member on this project',
            icon: 'error',
          });
          $(this).prop('disabled', true);
        }
      })

      $('.btn-remove-assignee').on('click', function () {
        if ($('input[name="assigned_to[]"]').length <= 1)
          swal({
            text: 'You cannot remove the only assignee of this task',
            icon: 'error',
          });
        else 
          $('#member-' + $(this).data('id')).remove();
      });

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

      $('.description').summernote('code', @json($task).description);
    })
  </script>
@endsection