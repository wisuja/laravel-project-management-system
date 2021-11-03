@extends('layouts.project')

@section('__content')
  {{ Breadcrumbs::render('project', $project) }}
  <h5 class="my-3">Setting</h5>
  <div class="row">
    <div class="col-3">
      <div class="nav flex-column nav-pills" id="setting-tab" role="tablist">
        <a class="nav-link active" id="general-tab" data-toggle="pill" href="#general" role="tab">General</a>
        <a class="nav-link" id="members-tab" data-toggle="pill" href="#members" role="tab">Members</a>
        <a class="nav-link" id="status-groups-tab" data-toggle="pill" href="#status-groups" role="tab">Status Groups</a>
        <a class="nav-link" id="labels-tab" data-toggle="pill" href="#labels" role="tab">Labels</a>
      </div>
    </div>
    <div class="col-9">
      <div class="tab-content" id="setting-tabContent">
        <div class="tab-pane fade show active" id="general" role="tabpanel">
          <form action="{{ route('projects.update') }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" value="{{ $project->id }}">
            <div class="form-group">
              <label for='name'>Name</label>
              <input type='text' name='name' id='name' class='form-control text-capitalize' value="{{ $project->name }}" required>
            </div>
            <div class="form-group">
              <label for='duration'>Duration</label>
              <input type='text' name='duration' id='duration' class='form-control' required>
            </div>
            <div class="form-group">
              <button class="btn btn-danger d-inline" type="reset">Reset</button>
              <button class="btn btn-primary d-inline" type="submit">Save</button>
            </div>
          </form>        
        </div>
        <div class="tab-pane fade" id="members" role="tabpanel">
          <div class="form-group">
            <label for='name'>Name</label>
            <input type='search' name='name' id='name' class='form-control' placeholder="Search user..." list="users">
          </div>
          <table class="table table-hover table-sm w-100 text-center" id="datatable-members">
            <thead>
              <tr>
                <th>No</th>
                <th>Name</th>
                <th></th>
              </tr>
            </thead>
          </table>
          <datalist id="users">
            @foreach ($users as $user)
              <option value="{{ $user->name }}">
            @endforeach
          </datalist>
        </div>
        <div class="tab-pane fade" id="status-groups" role="tabpanel">
          <div class="form-group">
            <label for='name'>Name</label>
            <input type='text' name='name' id='name' class='form-control' placeholder="Create a new status group...">
          </div>
          <div>
            <ul class="list-unstyled" id="sortable">
              @foreach ($project->statusGroups as $group)
                <li id="{{ $group->id }}" class="btn btn-light px-1 py-2 my-2 w-100 d-flex justify-content-between align-items-center">
                  <span>{{ $group->name }}</span>
                  <button type="button" class="close pb-1" onclick="removeGroup({{ $group->id }})">&times;</button>
                </li>
              @endforeach
            </ul>
          </div>
        </div>
        <div class="tab-pane fade" id="labels" role="tabpanel">
          <div class="form-group">
            <label for='name'>Name</label>
            <input type='search' name='name' id='name' class='form-control' placeholder="Add a new label...">
          </div>
          <table class="table table-hover table-sm w-100 text-center" id="datatable-labels">
            <thead>
              <tr>
                <th>No</th>
                <th>Name</th>
                <th></th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('__scripts')
  <script>
    function refreshMembers () {
      $('#datatable-members').DataTable().clear().destroy();
      $('#datatable-members').DataTable({
        processing: true,
        serverside: false,
        ajax: "{{ route('projects.members.index', ['project' => $project]) }}",
        columns: [
          {data: 'DT_RowIndex', orderable: true, searchable: true},
          {data: 'name'},
          {data: 'action', orderable: false, searchable: false}
        ]
      });
    }

    function refreshLabels () {
      $('#datatable-labels').DataTable().clear().destroy();
      $('#datatable-labels').DataTable({
        processing: true,
        serverside: false,
        ajax: "{{ route('projects.labels.index', ['project' => $project]) }}",
        columns: [
          {data: 'DT_RowIndex', orderable: true, searchable: true},
          {data: 'name'},
          {data: 'action', orderable: false, searchable: false}
        ]
      });
    }

    function removeMember (userId) {
      swal({
        text: 'Are you sure to delete this user?',
        showCancelButton: true,
        icon: 'warning',
        confirmButtonText: 'Confirm',
      }, function () {
        $.ajax({
          url: "/projects/{{ $project->id }}/members/" + userId,
          method: 'POST',
          data: {
            _token: '{{ csrf_token() }}',
            _method: 'DELETE'
          },
          success: function (response) {
            console.log(response);
            refreshMembers();
          },
          error: function (error) {
            console.log(error);
          }
        })
      })
    }

    function removeGroup (groupId) {
      swal({
        text: 'Are you sure to delete this group?',
        showCancelButton: true,
        icon: 'warning',
        confirmButtonText: 'Confirm',
      }, function () {
        $.ajax({
          url: '/projects/{{ $project->id }}/status-groups/' + groupId,
          method: 'POST',
          data: {
            _token: '{{ csrf_token() }}',
            _method: 'DELETE',
          },
          success: function (response) {
            console.log(response);
            $('#sortable').children(`#${groupId}`).remove();
            $('#sortable').sortable('refresh');
          },
          error: function (error) {
            console.error(error);
          }
        })
      })
    }

    function removeLabel (labelId) {
      swal({
        text: 'Are you sure to delete this label?',
        showCancelButton: true,
        icon: 'warning',
        confirmButtonText: 'Confirm',
      }, function () {
        $.ajax({
          url: "/projects/{{ $project->id }}/labels/" + labelId,
          method: 'POST',
          data: {
            _token: '{{ csrf_token() }}',
            _method: 'DELETE'
          },
          success: function (response) {
            console.log(response);
            refreshLabels();
          },
          error: function (error) {
            console.log(error);
          }
        })
      })
    }

    $(function () {
      $('input[name=duration]').daterangepicker({
        startDate: '{{ $project->from }}',
        endDate: '{{ $project->to }}',
        locale: {
          format: 'YYYY-MM-DD'
        }
      });

      $('#members input[name=name]').on('keydown', function (e) {
        if (e.which == 13) {
          let name = e.target.value;

          $.ajax({
            url: "{{ route('projects.members.store', ['project' => $project]) }}",
            method: 'POST',
            data: {
              _token: '{{ csrf_token() }}',
              name
            },
            beforeSend: function () {
              $('#members input[name=name]').prop('disabled', true);
            },
            success: function (user) {
              swal({
                title: 'Success',
                text: `Successfully add ${user.name} to the team`,
                icon: 'success',
                confirmButtonText: 'Ok'
              });
              refreshMembers();
            },
            error: function (error) {
              console.error(error);
            },
            complete: function () {
              $('#members input[name=name]').prop('disabled', false);
            },
            statusCode: {
              400: function () {
                swal({
                  title: 'Error',
                  text: `User already in the project group`,
                  icon: 'error',
                  confirmButtonText: 'Ok'
                });
              },
              404: function () {
                swal({
                  title: 'Error',
                  text: `User not found`,
                  icon: 'error',
                  confirmButtonText: 'Ok'
                });
              }
            }
          })
        } 
      })

      $('#status-groups input[name=name]').on('keydown', function (e) {
        if (e.which == 13) {
          let name = e.target.value;

          $.ajax({
            url: "{{ route('projects.status-groups.store', ['project' => $project]) }}",
            method: 'POST',
            data: {
              _token: '{{ csrf_token() }}',
              name
            },
            beforeSend: function () {
              $('#status-groups input[name=name]').prop('disabled', true);
            },
            success: function (group) {
              $('#sortable').append(`
                <li id="${group.id}" class="btn btn-light px-1 py-2 my-2 w-100 d-flex justify-content-between align-items-center">
                  <span>${group.name}</span>
                  <button type="button" class="close pb-1" onclick="removeGroup(${group.id})">&times;</button>
                </li>
              `);
            },
            error: function (error) {
              console.error(error);
            },
            complete: function () {
              $('#status-groups input[name=name]').val('');
              $('#status-groups input[name=name]').prop('disabled', false);
            }
          })
        }
      })

      $('#labels input[name=name]').on('keydown', function (e) {
        if (e.which == 13) {
          let name = e.target.value;

          $.ajax({
            url: "{{ route('projects.labels.store', ['project' => $project]) }}",
            method: 'POST',
            data: {
              _token: '{{ csrf_token() }}',
              name
            },
            beforeSend: function () {
              $('#labels input[name=name]').prop('disabled', true);
            },
            success: function (label) {
              swal({
                title: 'Success',
                text: `Successfully add ${label.name} to the team`,
                icon: 'success',
                confirmButtonText: 'Ok'
              });
              refreshLabels();
            },
            error: function (error) {
              console.error(error);
            },
            complete: function () {
              $('#labels input[name=name]').val('');
              $('#labels input[name=name]').prop('disabled', false);
            },
            statusCode: {
              400: function () {
                swal({
                  title: 'Error',
                  text: `Label already exists`,
                  icon: 'error',
                  confirmButtonText: 'Ok'
                });
              }
            }
          })
        }
      })

      $('#sortable').sortable({
        axis: 'y',
        update: function (event, ui) {
          $.ajax({
            url: "{{ route('projects.status-groups.update', ['projectId' => $project->id]) }}", 
            type: 'POST',
            data: {
              _token: '{{ csrf_token() }}',
              _method: 'PUT',
              groups: $(this).sortable('toArray')
            },
            success: function (response) {
              console.log(response)
            },
            error: function (error) {
              console.error(error);
            }
          });
        }
      });

      refreshMembers();
      refreshLabels();
    })
  </script>
@endsection