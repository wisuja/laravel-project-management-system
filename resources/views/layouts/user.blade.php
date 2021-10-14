@extends('layouts.app')

@section('title')
  @yield('title')
@endsection

@section('nav')
  @include('partials.user.nav')
@endsection

@section('content')
  @yield('_content')
@endsection

@section('scripts')
  @yield('_scripts')
@endsection