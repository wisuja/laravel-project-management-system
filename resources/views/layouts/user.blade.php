@extends('layouts.app')

@section('title')
  @yield('title')
@endsection

@section('_styles')
  @yield('styles')
@endsection

@section('nav')
  @include('partials.user.nav')
@endsection

@section('content')
  @yield('_content')
  @include('partials.footer')

  @include('partials.user.create-project-modal')
@endsection

@section('scripts')
  @yield('_scripts')

  <script>
    $('#createProjectModal input[name=duration]').daterangepicker({
      locale: {
        format: 'YYYY-MM-DD',
      }
    });
  </script>
@endsection