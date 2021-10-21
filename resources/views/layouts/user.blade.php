@extends('layouts.app')

@section('title')
  @yield('title')
@endsection

@section('nav')
  @include('partials.user.nav')
@endsection

@section('content')
  @yield('_content')

  @include('partials.user.create-project-modal')
@endsection

@section('scripts')
  @yield('_scripts')

  <script>
    $('#createProjectModal input[name=duration]').daterangepicker();
    $('#createProjectModal input[name=name]').on('input', function() {
      let name = $(this).val().split(' ').filter((word) => word !== '' && word.length >= 3);
      let code = '';
      if (name.length == 0) 
        code = '';
      else if (name.length == 1) 
        code = name[0].substr(0, 3);
      else if (name.length == 2)
        code = name[0].substr(0, 2) + name[1].substr(0,1);
      else 
        code = name[0].substr(0, 1) + name[1].substr(0, 1) + name[2].substr(0,1);

      $('#createProjectModal input[name=code]').val(code.toUpperCase());
    })
  </script>
@endsection