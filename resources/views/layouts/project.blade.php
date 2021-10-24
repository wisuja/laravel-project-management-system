@extends('layouts.user')

@section('title')
  {{ $project->name }}
@endsection

@section('_content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-3 p-3 bg-light" id="sidebar">
        <ul class="list-unstyled">
          <li class="py-3">
            <span class="font-weight-bold text-capitalize">{{ $project->name }}</span>
          </li>
          <li class="py-2">
            <button class="btn btn-light w-100 text-left">
              <i class="fas fa-tasks mr-1"></i>
              Backlog
            </button>
          </li>
          <li class="py-2">
            <button class="btn btn-light w-100 text-left">
              <i class="fas fa-clipboard mr-1"></i>
              Board
            </button>
          </li>
          <li class="py-2">
            <button class="btn btn-light w-100 text-left">
              <i class="fas fa-cogs mr-1"></i>
              Project Setting
            </button>
          </li>
        </ul>
      </div>
      <div class="col-9 p-3" id="content">
        <div>
          <span role="button" title="Click to toggle the sidebar" id="sidebar-toggle">
            <i class="fas fa-chevron-left text-secondary py-3" id="sidebar-toggle-icon"></i>
          </span>
        </div>
        @yield('__content')
      </div>
    </div>
  </div>
@endsection

@section('_scripts')
  <script>
    function toggleSidebar () {
      $('#sidebar').toggleClass('d-none');
      $('#content').toggleClass('col-9 col-12');
      $('#sidebar-toggle-icon').toggleClass('fa-chevron-right');
    }

    $(function () {
      $('#sidebar-toggle').on('click', toggleSidebar);
    })
  </script>
@endsection