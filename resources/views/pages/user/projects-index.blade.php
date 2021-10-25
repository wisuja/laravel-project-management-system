@extends('layouts.user')

@section('title')
  All Projects
@endsection

@section('_content')
  <div class="container">
    <div class="row mt-3">
      <div class="col-12">
        <h3>Projects</h3>
      </div>
    </div>
    <div class="row my-3">
      <div class="col-12 table-responsive">
        <table class="table table-hover table-sm text-center" id="datatable-projects">
          <thead>
            <tr>
              <th>
                <span role="button" id="star-all">
                  <i class="far fa-star text-dark"></i>
                </span>
              </th>
              <th>Name</th>
              <th>Code</th>
              <th>Lead</th>
              <th></th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
@endsection

@section('_scripts')
  <script>
    function toggleStar (id) {
      let isStarred = !$(`#star-${id} .fa-star`).hasClass('fas');

      $.ajax({
        url: "{{ route('projects.update') }}",
        method: 'POST',
        data: {
          _token: '{{ csrf_token() }}',
          _method: 'PUT',
          id,
          is_starred: isStarred ? 1 : 0
        },
        success: function (response) {
          $(`#star-${id} .fa-star`).toggleClass('far fas text-dark text-warning');
        }, 
        error: function (error) {
          console.log(error);
        }
      })
    }

    function remove (id) {
      $.ajax({
        url: '/projects/' + id,
        method: 'POST',
        data: {
          _token: '{{ csrf_token() }}',
          _method: 'DELETE',
          id
        },
        success: function (response) {
          console.log(response);
          window.location.reload();
        },
        error: function (error) {
          console.log(error);
          window.location.reload();
        }
      })
    }

    $(function () {
      $('#star-all').on('click', function () {
        $('#star-all i').toggleClass('far fas text-dark text-warning');

        $('[id^="star"]').not('#star-all').each(function () {
          let [ id ] = this.id.match(/\d+/g);
          toggleStar(id);
        })
      })

      $('#datatable-projects').DataTable({
        processing: true,
        serverside: false,
        ajax: '{{ route("projects.index") }}',
        columns: [
          {data: 'star'},
          {data: 'name'},
          {data: 'project.code'},
          {data: 'leader'},
          {data: 'action'}
        ],
        columnDefs: [{
          'targets': [0, 4],
          'searchable': false,
          'orderable': false,
        }]
      });
    });
  </script>
@endsection