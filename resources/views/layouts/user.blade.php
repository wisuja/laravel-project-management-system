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
    function readNotification () {
      $.ajax({
        url: "{{ route('read-notifications') }}",
        method: 'POST',
        data: {
          _token: '{{ csrf_token() }}'
        },
        success: function (response) {
          console.log(response);
        },
        error: function (error) {
          console.error(error);
        }
      })
    }

    $('#createProjectModal input[name=duration]').daterangepicker({
      locale: {
        format: 'YYYY-MM-DD',
      }
    });
  </script>
@endsection