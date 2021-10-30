@extends('layouts.app')

@section('title')
  @yield('title')
@endsection

@section('_styles')
  @yield('styles')
@endsection

@section('nav')
  @include('partials.home.nav')
@endsection

@section('content')
  @yield('_content')
  @include('partials.footer')
@endsection

@section('scripts')
  @yield('_scripts')
@endsection