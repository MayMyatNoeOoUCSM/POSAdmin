@extends('layouts.app')
@section('style')
  @yield('css')
@endsection
@section('content')
<body class="hold-transition sidebar-mini">
  <div class="wrapper">
      @include('layouts.header')
      @include('layouts.sidebar')
    
    <div class="content-wrapper">
      <section class="content-header">
        <div class="container-fluid">
          <h1>@yield('content-header')</h1>
        </div>
      </section>
      <section class="content">
        <div class="container-fluid">
          @yield('main-content')
        </div>
      </section>
    </div>
  </div>
  @yield('js')
</body>
@endsection