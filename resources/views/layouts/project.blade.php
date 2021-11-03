@extends('layouts.user')

@section('title')
  {{ $project->name }}
@endsection

@section('_styles')
  @yield('__styles')
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
            <a href="{{ route('projects.show', ['project' => $project, 'type' => 'backlog']) }}" class="btn btn-light w-100 text-left">
              <i class="fas fa-fw fa-tasks mr-1"></i>
              Backlog
            </a>
          </li>
          <li class="py-2">
            <a href="{{ route('projects.show', ['project' => $project, 'type' => 'boards']) }}" class="btn btn-light w-100 text-left">
              <i class="fas fa-fw fa-clipboard mr-1"></i>
              Board
            </a>
          </li>
          <li class="py-2">
            <a href="{{ route('projects.show', ['project' => $project, 'type' => 'setting']) }}" class="btn btn-light w-100 text-left">
              <i class="fas fa-fw fa-cogs mr-1"></i>
              Project Setting
            </a>
          </li>
        </ul>
      </div>
      <div class="col-9 p-3" id="content">
        <div>
          <span role="button" title="Click to toggle the sidebar" id="sidebar-toggle">
            <i class="fas fa-fw fa-chevron-left text-secondary py-3" id="sidebar-toggle-icon"></i>
            <span class="ml-1">Hide</span>
          </span>
        </div>
        @yield('__content')
      </div>
    </div>
  </div>
@endsection

@section('_scripts')
  <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
  <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>
  <script>
    function toggleSidebar () {
      $('#sidebar').toggleClass('d-none');
      $('#content').toggleClass('col-9 col-12');
      $('#sidebar-toggle-icon').toggleClass('fa-chevron-right');

      let isSidebarOpen = !$('#sidebar').hasClass('d-none');
      $('#sidebar-toggle span').text(isSidebarOpen ? 'Hide' : 'Expand');
    }

    $(function () {
      $('#sidebar-toggle').on('click', toggleSidebar);
    })
  </script>
  @yield('__scripts')
@endsection